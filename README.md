# Page teaser bundle for Contao

The content element "Page teaser" adds the page title (teaser title) and teaser text of one or multiple pages to the article, followed by a read more link. Clicking on this link will take you directly to the linked page. 

Optionally you can add a teaser image based on the functionality of the extension `terminal42/contao-pageimage`.

## Installation

Install the bundle via Composer:

```
composer require designerei/contao-page-teaser
```

## Features

- Add a teaser element including teaser title (default: page title), teaser text and read more link to the article
- Optionally add a teaser image via `terminal42/contao-pageimage`
- Choose one or multiple pages
- Order pages by page title or custom order
- Restrict page output
  - Default: Show all selected pages including their subpages
  - Show only selected pages
  - Show only subpages