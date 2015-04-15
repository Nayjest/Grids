Grids
=====

Grids framework for Laravel

[![Codacy Badge](https://www.codacy.com/project/badge/4c6955da466a45c1a64972bbfb81fcb7)](https://www.codacy.com/public/mail_2/Grids)
[![Code Climate](https://codeclimate.com/github/Nayjest/Grids/badges/gpa.svg)](https://codeclimate.com/github/Nayjest/Grids)
[![Circle CI](https://circleci.com/gh/Nayjest/Grids/tree/master.svg?style=svg)](https://circleci.com/gh/Nayjest/Grids/tree/master)
[![Release](https://img.shields.io/packagist/v/nayjest/grids.svg)](https://packagist.org/packages/nayjest/grids)
[![HHVM Status](http://hhvm.h4cc.de/badge/nayjest/grids.svg)](http://hhvm.h4cc.de/package/nayjest/grids)
[![Join the chat at https://gitter.im/Nayjest/Grids](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/Nayjest/Grids?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Both Laravel 4 and Laravel 5 are supported.

## Features
* Data providers (php array, Eloquent model, Doctrine DBAL query object)
* Themes support
* Individual views for UI components
* Twitter Bootstrap v3 used by default
* Caching
* Smart input handling allows to avoid conflicts with get parameters & easily place few interactive grids on same page
* Rich customization facilities
* Component architecture
* Declarative approach
* Constructing grids via strict object oriented API or configuration in php arrays
* Rich variety of components:
  - Excel and CSV export
  - _Records per page_ dropdown
  - Show/hide columns UI control
  - Sorting 
  - Filtering 
  - Totals calculation (sum, average value, records count, etc)
  - Pagination
  - etc

## Upcoming Features
* Autodetecting columns based on Eloquent model (if not specified)
* Builtin output formatters for different column types
* Working with json data sources via ajax
* Check compatibility with Lumen microframework

[Ask for more features](https://github.com/Nayjest/Grids/issues). You are welcome!

## Requirements

* Laravel 4.X / 5.X
* illuminate/html package if you use Laravel5.X
* php 5.4+

## Installation

##### Step 1: Install package using [Composer](https://getcomposer.org)

Add nayjest/grids to "require" section of your composer.json

```javascript
"require": {
    "nayjest/grids": "~0.7"
},
```

For Laravel 5 you also need to add "illuminate/html":

```javascript
"require": {
    "nayjest/grids": "~0.7",
    "illuminate/html": "~5"
},
```

Then install dependencies using following command:
```bash    
php composer.phar install
```

Instead of editing composer.json and executing _composer install_ you can just run following command:

For Laravel 4
```bash    
php composer.phar require nayjest/grids
```
For Laravel 5
```bash    
php composer.phar require nayjest/grids illuminate/html
```

##### Step 2: Laravel Setup
Add following line to 'providers' section of app/config/app.php file:
```php
'Nayjest\Grids\ServiceProvider',
```
For Laravel 5 you also need to add "illuminate/html" service provider:
```php
'Nayjest\Grids\ServiceProvider',
'Illuminate\Html\HtmlServiceProvider',
```

You may also add facade aliases to your application configuration:
```php
    'Form'  => 'Illuminate\Html\FormFacade',
    'HTML'  => 'Illuminate\Html\HtmlFacade',
    'Grids'     => 'Nayjest\Grids\Grids',
```
## Demo

Demonstration available [here](http://grids-demo.herokuapp.com/demo/example4)

[Code](https://github.com/Nayjest/grids-demo)



## Usage

#### Basic example

In example below grid is configured by php array using [Nayjest/Builder](https://github.com/Nayjest/Builder) package facilities.

```php
$cfg = [
    'src' => 'App\User',
    'columns' => [
            'id',
            'name',
            'email',
            'country'
    ]
];
echo Grids::make($cfg);
```

Results available [here](http://grids-demo.herokuapp.com/demo/example1). For more details see [demo application repository](https://github.com/Nayjest/grids-demo/blob/master/app/Http/Controllers/DemoController.php)

#### Advanced example

If you don't like plain arrays, you can construct grids using object oriented api:

##### Step 1. Instantiate & Configure Grid

See example below

```php
# Let's take a Eloquent query as data provider
# Some params may be predefined, other can be controlled using grid components         
$query = (new User)
    ->newQuery()
    ->with('posts')
    ->where('role', '=', User::ROLE_AUTHOR);

# Instantiate & Configure Grid
$grid = new Grid(
    (new GridConfig)
        # Grids name used as html id, caching key, filtering GET params prefix, etc
        # If not specified, unique value based on file name & line of code will be generated
        ->setName('my_report')
        # See all supported data providers in sources
        ->setDataProvider(new EloquentDataProvider($query))
        # Setup caching, value in minutes, turned off in debug mode
        ->setCachingTime(5) 
        # Setup table columns
        ->setColumns([
            # simple results numbering, not related to table PK or any obtained data
            new IdFieldConfig,
            (new FieldConfig)
                ->setName('login')
                # will be displayed in table heder
                ->setLabel('Login')
                # That's all what you need for filtering. 
                # It will create controls, process input 
                # and filter results (in case of EloquentDataProvider -- modify SQL query)
                ->addFilter(
                    (new FilterConfig)
                        ->setName('login')
                        ->setOperator(FilterConfig::OPERATOR_LIKE)
                )
                # optional, 
                # use to prettify output in table cell 
                # or print any data located not in results field matching column name
                ->setCallback(function ($val, EloquentDataRow $row) {
                    if ($val) {
                        $icon  = "<span class='glyphicon glyphicon-user'></span>&nbsp;";
                        $user = $row->getSrc();
                        return $icon . HTML::linkRoute('users.profile', $val, [$user->id]);
                    }
                })
                # sorting buttons will be added to header, DB query will be modified
                ->setSortable(true)
            ,
            (new FieldConfig)
                ->setName('status')
                ->setLabel('Status')
                ->addFilter(
                    (new SelectFilterConfig)
                        ->setOptions(User::getStatuses())
                )
            ,
            (new FieldConfig)
                ->setName('country')
                ->setLabel('Country')
                ->addFilter(
                    (new SelectFilterConfig)
                        ->setName('country')
                        ->setOptions(get_countries_list())
                )
            ,
            (new FieldConfig)
                ->setName('registration_date')
                ->setLabel('Registration date')
                ->setSortable(true)
            ,
            (new FieldConfig)
                ->setName('comments_count')
                ->setLabel('Comments')
                ->setSortable(true)
            ,
            (new FieldConfig)
                ->setName('posts_count')
                ->setLabel('Posts')
                ->setSortable(true)
            ,
        ])
        # Setup additional grid components
        ->setComponents([
            # Renders table header (table>thead)
            (new THead)
                # Setup inherited components
                ->setComponents([
                    # Add this if you have filters for automatic placing to this row
                    new FiltersRow,
                    # Row with additional controls
                    (new OneCellRow)
                        ->setComponents([
                            # Control for specifying quantity of records displayed on page
                            (new RecordsPerPage)
                                ->setVariants([
                                    50,
                                    100,
                                    1000
                                ])
                            ,
                            # Control to show/hide rows in table
                            (new ColumnsHider)
                                ->setHiddenByDefault([
                                    'activated_at',
                                    'updated_at',
                                    'registration_ip',
                                ])
                            ,
                            # Submit button for filters. 
                            # Place it anywhere in the grid (grid is rendered inside form by default).
                            (new HtmlTag)
                                ->setTagName('button')
                                ->setAttributes([
                                    'type' => 'submit',
                                    # Some bootstrap classes
                                    'class' => 'btn btn-primary'
                                ])
                                ->setContent('Filter')
                        ])
                        # Components may have some placeholders for rendering children there.
                        ->setRenderSection(THead::SECTION_BEGIN)
                ])
            ,
            # Renders table footer (table>tfoot)
            (new TFoot)
                ->addComponent(
                    # TotalsRow component calculates totals on current page
                    # (max, min, sum, average value, etc)
                    # and renders results as table row.
                    # By default there is a sum.
                    new TotalsRow([
                        'comments',
                        'posts',
                    ])
                )
                ->addComponent(
                    # Renders row containing one cell 
                    # with colspan attribute equal to the table columns count
                    (new OneCellRow)
                        # Pagination control
                        ->addComponent(new Pager)
                )
        ])
);
```

#### Step 2. Render Grid
```
<?php echo $grid->render(); ?>

# Example below will also work as Grid class implements __toString method.
# Note that you can't forward Exceptions through __toString method on account of PHP limitations.
# Therefore you can preliminarily render grid in debug reasons and then pass resutls to view.
<?php echo $grid; ?>

# or shorter
<?= $grid ?>
# or using blade syntax (Laravel 5)
{!! $grid !!}
```        

####Notes
* Class names in example code used without namespaces therefore you must import it before
* Grids does not includes Twitter Bootstrap css/js files to your layout. You need to do it manually
Quick links:
```html
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
```
* Nayjest\Grids\Components\Pager component works only with Laravel 4.X, for Laravel 5 use Nayjest\Grids\Components\Laravel5\Pager


## Upgrade Guide

### From 0.3.X to 0.4+

1. Use THead & TFoot instead of Header & Footer components
2. If you have customized grid view (grid.php), refactor it using changes in default view
3. Some components became default, so you don't need to add it to configuration

Default components hierarchy:
```
- GridConfig
    - THead
        - ColumnHeadersRow
        - FiltersRow
    - TFoot
        - OneCellRow
            - Pager
        
```
For adding child components to default one, resolve it by name and use addComponent / addComponents methods.

Example:
```php
...
(new GridConfig)
    ->setDataProvider($provider)
    ->getComponentByName(THead::NAME)
        ->getComponentByName(FiltersRow::NAME)
            ->addComponent(
                (new HtmlTag)
                    ->setTagName('button')
                    ->setContent('Filter')
                    ->setAttributes([
                        'type' => 'submit',
                        'class' => 'btn btn-success btn-sm'
                    ])
                    ->setRenderSection('filters_row_column_Actions')
            )
            ->getParent()
        ->getParent()
    ->setColumns([
...    
```

Note that setComponents method rewrites components structure provided by defaults.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email mail@vitaliy.in instead of using the issue tracker.

## License

© 2014 &mdash; 2015 Vitalii Stepanenko

Licensed under the MIT License. 

Please see [License File](LICENSE) for more information.
