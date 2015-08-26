# Content Service

## Pages

### Properties

| Property         | Explanation                                                                                  |
| -----------------| -------------------------------------------------------------------------------------------- |
| id               | Unique identifier for the page.                                                              |
| store_id         | Id of the store associated with the page.                                                    |
| published        | Whether the page is published in the storefront or not (true = published, false= draft).     |
| name             | I18n object containing the title of the page                                                 |
| handle           | I18n object containing the handle (url slug) of the page                                     |
| content          | I18n object containing the html content of the page                                          |
| seo_title        | I18n object containing the SEO title of the page. Can be null.                               |
| seo_description  | I18n object containing the SEO description of the page. Can be null.                         |
| created_at       | Date and time for the creation of the page                                                   |
| updated_at       | Date and time for the last update of the page                                                |

### Example

```json
{
  "id": 12345,
  "store_id": 46,
  "published": true,  
  "name": {
    "es_AR": "Quiénes somos",
    "pt_BR": "Quem somos",
    "en_US": "About us"
  },
  "handle": {    
    "es_AR": "quienes-somos",
    "pt_BR": "quem-somos",
    "en_US": "about-us"
  },
  "content": {    
    "es_AR": "<p>Silph Co. es el fabricante líder de tecnología pokemon. Desarrollamos las pokebolas más utilizadas en el mercado junto con otros productos relacionados con pokemon, incluyendo medicina y TMs.</p>",
    "pt_BR": "<p>Silph Co. é a fabricante líder de tecnologia pokemon. Desenvolvemos as pokebolas mais utilizadas e vários outros itens relacionados com os Pokémon, incluindo a medicina e TMs.</p>",
    "en_US": "<p>Silph Co. is the leading manufacturer of pokemon technology. We develop the most commercially used Poke Balls and several other pokemon-related items, including medicine and TMs.</p>"
  },
  "seo_title": {    
    "es_AR": "Descubrí la historia de Sliph Co.",
    "pt_BR": "Descubra a história da Silph Co.",
    "en_US": "Discover the history of Silph Co."
  },
  "seo_description": {    
    "es_AR": "Silph Co. es el fabricante líder de tecnología pokemon. Desarrollamos las pokebolas más utilizadas en el mercado junto con otros productos relacionados con pokemon, incluyendo medicina y TMs.",
    "pt_BR": "Silph Co. é a fabricante líder de tecnologia pokemon. Desenvolvemos as pokebolas mais utilizadas e vários outros itens relacionados com os Pokémon, incluindo a medicina e TMs.",
    "en_US": "Silph Co. is the leading manufacturer of pokemon technology. We develop the most commercially used Poke Balls and several other pokemon-related items, including medicine and TMs."
  },
  "created_at": "2015-02-06T14:07:23+0000",
  "updated_at": "2015-02-06T14:07:23+0000"
}
```

### GET /{$store_id}/pages
Get the list of all pages. The following filters may be applied as URLs params:

| Filter      | Explanation                                                                                     |
| ------------| ----------------------------------------------------------------------------------------------- |
| ids         | Get the pages associated with the given comma-separated ids. Useful to group multiple API calls into one.  |
| published   | Filter by published/draft status (true = published only, false= draft only, not present= all).  |
| handle      | Filter pages with a specific handle. Must provide a language.                                   |
| q           | Filter pages whose title contains the given string. Must provide a language.                    |
| langauge    | Language to use when performing a text-based search.                                            |
| page        | Number of page to query.                                                                        |
| per_page    | How many pages to show per page.                                                                |

### GET /{$store_id}/pages/#{id}
Get a single page by its id.

### POST /{$store_id}/pages
Create a new page.

TODO Document mandatory attributes.

### PUT /{$store_id}/pages/#{id}
Modify a page by its id.

### DELETE /{$store_id}/pages/#{id}
Delete a page by its id.

### GET /{$store_id}/pages/handle
Get handles that potentially collide with the given one. Collide here means that the handle is equal to the provided one plus zero or more digits at the end.

`handle` and `language` are mandatory arguments.

#### Examples

**GET /1272/pages/handle?language=es_AR&handle=quienes-somos**

```json
[
    "quienes-somos",
    "quienes-somos1"
]
```

**GET /1272/pages/handle?language=es_AR&handle=new-page**

```json
[]
```
