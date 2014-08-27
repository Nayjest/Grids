<?php /** @var Nayjest\Grids\Components\ColumnsHider $component */ ?>
<span data-role="columns-hider">
    <button
        id="<?= $component->getId('btn') ?>"
        class="btn btn-default"
        data-toggle="popover"
        type="button"
        data-placement="bottom"
        >
        <span class="glyphicon glyphicon-eye-open"></span>
        Show/hide columns
    </button>
    <div id="<?= $component->getId('menu-content') ?>" style="display: none">
        <ul>
            <li>
                <label>
                    <input type="checkbox" name="all">
                    <b><?= t('Show/Hide all') ?></b>
                </label>
            </li>
            <?php foreach($columns as $column):
            /** @var Nayjest\Grids\FieldConfig $column */
            ?>
            <li>
                <label>
                    <input type="checkbox" name="<?= $column->getName() ?>">
                    <?= $column->getLabel() ?>
                </label>
            </li>
            <?php endforeach ?>
        </ul>
    </div>
</span>
<script>
    $(function () {

        var cookie = {
            set: function (name, value, options) {
                options = options || {};

                var expires = options.expires;

                if (typeof expires == "number" && expires) {
                    var d = new Date();
                    d.setTime(d.getTime() + expires*1000);
                    expires = options.expires = d;
                }
                if (expires && expires.toUTCString) {
                    options.expires = expires.toUTCString();
                }

                value = encodeURIComponent(value);

                var updatedCookie = name + "=" + value;

                for(var propName in options) {
                    updatedCookie += "; " + propName;
                    var propValue = options[propName];
                    if (propValue !== true) {
                        updatedCookie += "=" + propValue;
                    }
                }

                document.cookie = updatedCookie;
            },
            get: function(name) {
                var matches = document.cookie.match(new RegExp(
                    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
                ));
                return matches ? decodeURIComponent(matches[1]) : undefined;
            }
        };

        // popover
        var menu = $('#<?= $component->getId('menu-content') ?>').html();
        $('#<?= $component->getId('btn') ?>').popover({
            content: menu,
            html: true
        });

        // column hider
        var ColumnHider = function (switcherSelector, tableSelector) {
            this.switcherSelector = switcherSelector;
            this.tableSelector = tableSelector;
            //this.cookiePath = "{{ parse_url(URL::current())['path'] }}";
        };
        ColumnHider.prototype.defaultValues = {
            views: true,
            hosts: true,
            regs: true,
            gamers: true,
            rounds: true,
            first_deposits: true,
            deposits: true,
            withdraws: true,
            qualified_players: true
            <?php if (rebills_view_allowed()): ?>
            ,
            rebills_looses: false,
            rebills_profit: false
            <?php endif ?>
        };
        ColumnHider.prototype.getValues = function () {
            var json;
            if (!this.values) {
                if (json = cookie.get('show_columns')) {
                    this.values = JSON.parse(json);
                } else {
                    this.values = this.defaultValues;
                }
            }
            return this.values;
        };
        ColumnHider.prototype.updateInputs = function () {
            var values = this.getValues();
            var $input;
            for (var name in values) {
                if (values.hasOwnProperty(name)) {
                    $input = $(this.switcherSelector + ' input[name="' + name + '"]');
                    $input.attr('checked', values[name]);
                }
            }
        }

        ColumnHider.prototype.getColumnElements = function (name) {
            return $('.column-' + name);
        };

        ColumnHider.prototype.saveValues = function() {
            cookie.set(
                'show_columns',
                JSON.stringify(this.getValues()),
                {path: '/'}
            );
        };

        ColumnHider.prototype.setValue = function (name, value) {
            var me = this;
            if(name === 'all') {
                $.each(this.getValues(), function(i){
                    me.getValues()[i] = value;
                    $('input[name='+i+']').prop('checked', value);
                    me.updateColumnVisibility(i, value);
                });
            } else {
                this.getValues()[name] = value;
                this.updateColumnVisibility(name, value);
            }
            this.checkAll();
        };

        ColumnHider.prototype.checkAll = function() {
            var checked = true,
                me = this;
            $.each(this.getValues(), function(i){
                if(i === 'all') return;
                checked = me.getValues()[i] && checked;
            });
            this.getValues()['all'] = checked;
            $('input[name=all]').prop('checked', checked);
            this.saveValues();
        };

        ColumnHider.prototype.updateColumnVisibility = function (name, visible) {
            var $els = this.getColumnElements(name);
            visible ? $els.show('slow') : $els.hide('slow');
        }

        ColumnHider.prototype.setupInputs = function () {
            var self = this;
            var handler = function () {
                var checked = $(this).is(':checked');
                var name = $(this).attr('name');
                self.setValue(name, checked);
            };
            $(self.switcherSelector + ' input').on('change', handler);
        }

        ColumnHider.prototype.setup = function () {
            var self = this;
            // @todo this.tableSelector may not be corresponding element depending on markup
            $(this.tableSelector).on('shown.bs.popover', function () {
                self.setupInputs();
                self.updateInputs();
            });
            this.setupTable();
        }
        ColumnHider.prototype.setupTable = function () {
            var values = this.getValues();
            for (var name in values) {
                if (values.hasOwnProperty(name) && !values[name]) {
                    this.getColumnElements(name).hide(0);
                }
            }
        }

        var ch = new ColumnHider(
            '#<?= $grid->getConfig()->getName() ?>',
            '#<?= $component->getId('btn') ?>'
        );
        ch.setup();
        ch.saveValues();
    });

</script>
