<?php namespace Laravel\Database\Eloquent;

class Hydrator {

    /**
     * Load the array of hydrated models and their eager relationships.
     *
     * @param  Model  $eloquent
     * @return array
     */
    public static function hydrate($eloquent)
    {
        $results = static::base(get_class($eloquent), $eloquent->query->get());

        // Begin Tienda Nube nested eager loading
        static::auxiliary_hydrate($eloquent, $results);
        // End Tienda Nube nested eager loading

        return $results;
    }

    private static function auxiliary_hydrate(&$eloquent, &$results) {
        // Begin Tienda Nube nested eager loading
        if(count($results) > 0) {

            $eager_tree = [];

            foreach($eloquent->includes as $include) {
                if(is_string($include)) {
                    $fields = explode('.', $include);

                    $current_eager_subtree = &$eager_tree;

                    foreach ($fields as $field) {
                        if(!isset($current_eager_subtree[$field])) {
                            $current_eager_subtree[$field] = [];
                        }
                        $current_eager_subtree = &$current_eager_subtree[$field];
                    }
                } else {
                    $eager_tree = array_merge_recursive($eager_tree, $include);
                }
            }

            foreach ($eager_tree as $include => $nested)
            {
                if ( ! is_callable(array($eloquent, $include)))
                {
                    throw new \LogicException("Attempting to eager load [$include], but the relationship is not defined.");
                }

                static::eagerly($eloquent, $results, $include, $nested);

            }

        }
        // End Tienda Nube nested eager loading
    }

    /**
     * Hydrate the base models for a query.
     *
     * The resulting model array is keyed by the primary keys of the models.
     * This allows the models to easily be matched to their children.
     *
     * @param  string  $class
     * @param  array   $results
     * @return array
     */
    private static function base($class, $results)
    {
        $models = array();

        foreach ($results as $result)
        {
            $model = new $class;

            $model->attributes = (array) $result;

            $model->exists = true;

            if (isset($model->attributes['id']))
            {
                $models[$model->id] = $model;
            }
            else
            {
                $models[] = $model;
            }
        }

        return $models;
    }

    /**
     * Eagerly load a relationship.
     *
     * @param  object  $eloquent
     * @param  array   $parents
     * @param  string  $include
     * @return void
     */
    private static function eagerly($eloquent, &$parents, $include, $next_eager = null)
    {
        // We temporarily spoof the query attributes to allow the query to be fetched without
        // any problems, since the belongs_to method actually gets the related attribute.
        $first = reset($parents);

        $eloquent->attributes = $first->attributes;

        $relationship = $eloquent->$include();

        $eloquent->attributes = array();

        // Reset the WHERE clause and bindings on the query. We'll add our own WHERE clause soon.
        // This will allow us to load a range of related models instead of only one.
        $relationship->query->reset_where();

        // Begin Tienda Nube eager loading modification
        if($next_eager) {
            $relationship->with($next_eager);
        }

        //FIX for relationships with a WHERE clause
        if( property_exists($relationship, 'eager_where') and $relationship->eager_where ) {
            $relationship->query->raw_where($relationship->eager_where, $relationship->eager_where_params, $relationship->eager_where_connector);
            $relationship->eager_where = null;
            $relationship->eager_where_params = array();
            $relationship->eager_where_connector = 'AND';
        }

        // End Tienda Nube eager loading modification

        // Initialize the relationship attribute on the parents. As expected, "many" relationships
        // are initialized to an array and "one" relationships are initialized to null.
        foreach ($parents as &$parent)
        {
            $parent->ignore[$include] = (in_array($eloquent->relating, array('has_many', 'has_and_belongs_to_many'))) ? array() : null;
        }

        if (in_array($relating = $eloquent->relating, array('has_one', 'has_many', 'belongs_to')))
        {
            return static::$relating($relationship, $parents, $eloquent->relating_key, $include);
        }
        else
        {
            static::has_and_belongs_to_many($relationship, $parents, $eloquent->relating_key, $eloquent->relating_table, $include);
        }
    }

    /**
     * Eagerly load a 1:1 relationship.
     *
     * @param  object  $relationship
     * @param  array   $parents
     * @param  string  $relating_key
     * @param  string  $relating
     * @param  string  $include
     * @return void
     */
    private static function has_one($relationship, &$parents, $relating_key, $include)
    {
        foreach ($relationship->where_in($relating_key, array_keys($parents))->get() as $key => $child)
        {
            $parents[$child->$relating_key]->ignore[$include] = $child;
        }
    }

    /**
     * Eagerly load a 1:* relationship.
     *
     * @param  object  $relationship
     * @param  array   $parents
     * @param  string  $relating_key
     * @param  string  $relating
     * @param  string  $include
     * @return void
     */
    private static function has_many($relationship, &$parents, $relating_key, $include)
    {
        foreach ($relationship->where_in($relating_key, array_keys($parents))->get() as $key => $child)
        {
            $parents[$child->$relating_key]->ignore[$include][$child->id] = $child;
        }
    }

    /**
     * Eagerly load a 1:1 belonging relationship.
     *
     * @param  object  $relationship
     * @param  array   $parents
     * @param  string  $relating_key
     * @param  string  $include
     * @return void
     */
    private static function belongs_to($relationship, &$parents, $relating_key, $include)
    {
        $keys = array();

        foreach ($parents as &$parent)
        {
            $keys[] = $parent->$relating_key;
        }

        $children = $relationship->where_in('id', array_unique($keys))->get();

        foreach ($parents as &$parent)
        {
            if (array_key_exists($parent->$relating_key, $children))
            {
                $parent->ignore[$include] = $children[$parent->$relating_key];
            }
        }
    }

    /**
     * Eagerly load a many-to-many relationship.
     *
     * @param  object  $relationship
     * @param  array   $parents
     * @param  string  $relating_key
     * @param  string  $relating_table
     * @param  string  $include
     *
     * @return void
     */
    private static function has_and_belongs_to_many($relationship, &$parents, $relating_key, $relating_table, $include)
    {
        // The model "has and belongs to many" method sets the SELECT clause; however, we need
        // to clear it here since we will be adding the foreign key to the select.
        $relationship->query->select = null;

        $relationship->query->where_in($relating_table.'.'.$relating_key, array_keys($parents));

        // The foreign key is added to the select to allow us to easily match the models back to their parents.
        // Otherwise, there would be no apparent connection between the models to allow us to match them.
        $children = $relationship->query->select(array(Model::table(get_class($relationship)).'.*', $relating_table.'.'.$relating_key))->get();

        $class = get_class($relationship);

        // Begin Tienda Nube eager load modification

        $relateds = [];

        // We make two loops. The first one (this one, as well as the following auxiliary_hydrate)
        // is to create and populate all the possible children.
        foreach ($children as $child)
        {
            $related = new $class;

            $related->attributes = (array) $child;

            $related->exists = true;

            // Remove the foreign key since it was only added to the query to help match the models.
            unset($related->attributes[$relating_key]);

            $relateds[$child->id] = $related;

        }

        static::auxiliary_hydrate($relationship, $relateds);

        // This second loop is to actually populate the parents with the children.
        foreach ($children as $child) {
            $parents[$child->$relating_key]->ignore[$include][$child->id] = $relateds[$child->id];
        }

        // End Tienda Nube eager load modification
    }

}
