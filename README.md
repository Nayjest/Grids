Grids
=====

Grids framework for Laravel

## Requirements

* Laravel 4.2+
* php 5.4+

## Installation

#### Installation with composer

* Step 1: Add git url to composer.json file in your project:
```
"repositories": [
    {
        "url": "https://github.com/Nayjest/Grids.git",
        "type": "git"
    }
],
```
* Step 2: Add dependency to "require" section
```
"require": {
    "nayjest/grids": "dev-master"
},
```
* Step 3: run "composer update" command

## Usage

*Example*

```php
        # Step 1. 
        # Let's take a Laravel query as data provider
        # Some params may be predefined, other can be controlled using grid components         
        $query = (new User)
            ->newQuery()
            ->with('posts')
            ->where('role', '=', User::ROLE_AUTHOR);
        
        # Step 2. 
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
                        ->setIsSortable(true)
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
                        ->setIsSortable(true)
                    ,
                    (new FieldConfig)
                        ->setName('comments_count')
                        ->setLabel('Comments')
                        ->setIsSortable(true)
                    ,
                    (new FieldConfig)
                        ->setName('posts_count')
                        ->setLabel('Posts')
                        ->setIsSortable(true)
                    ,
                ])
                # Setup additional grid components
                ->setComponents([
                    # Renders table header (table>thead)
                    (new Header)
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
                                ->setRenderSection(Header::SECTION_BEGIN)
                        ])
                    ,
                    # Renders table footer (table>tfoot)
                    (new Footer)
                        ->addComponent(
                            # TotalsRow component calculates totals on current page (max, min, sum, average value, etc)
                            # and renders results as table row.
                            # By default 'sum' algorithm used.
                            new TotalsRow([
                                'comments',
                                'posts',
                            ])
                        )
                        ->addComponent(
                            # Renders row containing one cell with colspan attribute equal to the table columns count
                            (new OneCellRow)
                                # Pagination control
                                ->addComponent(new Pager)
                        )
                ])
        );
        
        # Step 3.
        # Render grid (preferred in view)
        
        <?php echo $grid->render(); ?>
        
        # Example below will also work as Grid class implements __toString method.
        # Note that you can't forward Exceptions through __toString method on account of PHP limitations.
        # Therefore you can preliminarily render grid in debug reasons and pass the resutls to a view.
        <?php echo $grid; ?>
        
        # And the shortest way:
        <?= $grid ?>
```

## License


© 2014 Vitalii Stepanenko

Licensed under the MIT License
