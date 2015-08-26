<?php

class Pages_Controller extends Controller {

    public $restful = true;

    public $adapter;

    public function __construct($adapter){
        $this->adapter = $adapter;
        $this->filter('after', 'tag_page');
    }

    /**
     * GET /pages
     */
    public function get_page_list($store_id) {
        // Validations
        $errors = $this->validate([
            'ids' => 'integer_comma_list',
            'store_id' => 'integer',
            'per_page' => 'integer|between:1,200',
            'page' => 'integer|min:1',
            'published' => 'boolean',
            'handle' => 'requires:language',
            'q' => 'requires:language',
            'language' => 'in:es_AR,pt_BR,en_US',
        ]);

        if ($errors != null){
            return $errors;
        }

        // Filters
        $query = Page::where_null('deleted_at')->where_store_id($store_id);

        if (Input::has('ids')){
            $ids = explode(',', Input::get('ids'));
            $query->where_in('id', $ids);
        }

        if (Input::has('published')){
            $query->where_publish(Input::get('published') == "true" ? 1 : 0);
        }

        if (Input::has('language')){
            $query->join(Page_I18n::$table, Page::$table . '.id', '=', 'page_id')->where_lang(Input::get('language'));

            if (Input::has('handle')){
                $query->where('nice-name', '=', Input::get('handle'));
            }

            if (Input::has('q')){
                $query->where('title', 'LIKE', '%' . Input::get('q') . '%');
            }
        }

        // Response
        $limit = Input::get('per_page', 20);
        $pages = $query->paginate($limit, [Page::$table . '.*']);
        $result = $this->adapter->format(array_values($pages->results));
        return Response::json($result)->header('X-Total-Count', $pages->total);
    }

    /**
     * GET /pages/id
     */
    public function get_page($store_id, $id) {
        $page = Page::find($id);

        return Response::json($this->adapter->format($page));
    }

    /**
     * POST /pages
     */
    public function post_page($storeId) {

        // TODO: Better validations

        // Validations
        $errors = $this->validate([
            'published' => 'boolean|required',
        ]);

        if ($errors != null) {
            return $errors;
        }

        $data = Input::all();

        $pageLangs = $this->createPageLangs($data);
        $pageService = new PageService($storeId);
        $published = $this->boolValue($data['published']);
        $page = $pageService->save($published, $pageLangs);

        return Response::json($this->adapter->format($page));
    }

    /**
     * PUT /pages/id
     */
    public function put_page($storeId, $id) {

        // TODO: Better validations

        // Validations
        $errors = $this->validate([
            'published' => 'boolean|required',
        ]);

        if ($errors != null) {
            return $errors;
        }

        $data = Input::all();

        $pageLangs = $this->createPageLangs($data);
        $pageService = new PageService($storeId);
        $published = $this->boolValue($data['published']);
        $page = Page::find($id);
        $page->publish = $published;
        $pageService->update($page, $pageLangs);

        return Response::json($this->adapter->format($page));
    }

    /**
     * DELETE /pages/id
     */
    public function delete_page($store_id, $id) {
        $page = Page::find($id);
        $page->delete();

        return Response::json([]);
    }

    /**
     * TODO: Move to an abstract class if a second controller is made
     */
    private function validate($rules){
        $validator = Validator::make(Input::all(), $rules);

        if (!$validator->valid()){
            return Response::json($validator->errors->all(), 422);
        }
    }

    /**
     * GET /pages/handle
     */
    public function get_page_handle($store_id) {
        // Validations
        $errors = $this->validate([
            'handle' => 'required',
            'language' => 'required|in:es_AR,pt_BR,en_US',
        ]);

        if ($errors != null){
            return $errors;
        }

        // Query data
        $escapedHandle = preg_quote(Input::get('handle'));
        $rows = DB::table(Page_I18n::$table)
            ->join(Page::$table, Page::$table . '.id', '=', 'page_id')
            ->where(Page::$table . '.store_id', '=', $store_id)
            ->raw_where("`nice-name` REGEXP '^($escapedHandle){1}[0-9]*$'")
            ->where_lang(Input::get('language'))
            ->where_null(Page::$table . '.deleted_at')
            ->select(['nice-name'])
            ->get();

        $handles = [];
        foreach($rows as $row){
            $handles[] = $row->{'nice-name'};
        }

        // Response
        return Response::json($handles);
    }

    private function createPageLangs($data) {
        $pageLangs = [];
        foreach(['es_AR', 'pt_BR', 'en_US'] as $lang) {
            $pageLangs[$lang] = new PageLangDTO(
                isset($data['name'][$lang]) ? $data['name'][$lang] : null,
                isset($data['content'][$lang]) ? $data['content'][$lang] : null,
                isset($data['seo_title'][$lang]) ? $data['seo_title'][$lang] : null,
                isset($data['seo_description'][$lang]) ? $data['seo_description'][$lang] : null,
                isset($data['handle'][$lang]) ? $data['handle'][$lang] : null );
        }
        return $pageLangs;
    }

    private function boolValue($str) {
        if ($str === 'true') {
            return true;
        } else if ($str === 'false') {
            return false;
        }
        throw new InvalidArgumentException($str);
    }

}
