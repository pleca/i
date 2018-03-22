<?php require_once($_SERVER ['DOCUMENT_ROOT'].'/header.php'); ?>
<?php
if(!in_array('18', $luzu)){
    header('Location: https://' . $_SERVER ['HTTP_HOST'] . '/403 ');
}
tytul_strony('E-TASK');
?>
    <script type="text/javascript" src="<?php adres_strony(); ?>biblioteki/bootstrap/js/bootstrap.js"></script>

    <script src="<?php adres_strony(); ?>biblioteki/telerik/js/jquery.min.js"></script>
    <script src="<?php adres_strony(); ?>biblioteki/telerik/js/kendo.all.min.js"></script>
    <script id="skrypty" type="text/javascript" src="<?php echo adres_strony(); ?>moduly/e-task/js/funkcje"></script>
    <link rel="stylesheet" href="<?php adres_strony(); ?>biblioteki/telerik/styles/kendo.common.min.css" />
    <link rel="stylesheet" href="<?php adres_strony(); ?>biblioteki/telerik/styles/kendo.default.min.css" />
    <link rel="stylesheet" href="<?php adres_strony(); ?>biblioteki/telerik/styles/kendo.default.mobile.min.css" />
    <link rel="stylesheet" href="<?php echo 'https://' . $_SERVER ['HTTP_HOST'] . '/moduly/e-task/css/style.css'; ?>" type="text/css" />


<script id="popup-editor" type="text/x-kendo-template">

    <ul class="fieldlist">
        <li>
            <label for="simple-input">Zakres dat</label>
            Data zdarzenia: <input data-role="datepicker" name="StartEvent" type="text" style="width: 20%;"/>
            Koniec zdarzenia: <input data-role="datepicker" name="EndEvent" type="text" style="width: 20%;"/>
        </li>
        <li>
            <label for="simple-input">Dane poszkodowanego</label>
            Imię: <input name="VictimFirstName" type="text" class="k-input k-textbox" style="width: 30%;"/>
            Nazwisko: <input name="VictimLastName" type="text" class="k-input k-textbox" style="width: 30%;"/>
        </li>
        <li>
            <label for="simple-input">Miejsce zdarzenia</label>
            <div id="province" style="width: 30%; float: left;"> Województwo:
                <select data-role="dropdownlist"
                        data-option-label="Wybierz województwo"
                        data-value-primitive="true"
                        data-filter="contains"
                        data-value-field="Id"
                        data-text-field="Name"
                        name="EventProvince"
                        data-bind="value: EventProvinceID, source: type"
                        >
                </select>
            </div>
            <script>

                var dataSource = new kendo.data.DataSource({
                    transport: {
                        read: {
                            type: "POST",
                            url: "https://192.168.4.14/lead/get_province",
                            dataType: "json"
                        }
                    }
                });

                var viewModel = kendo.observable({
                    Province: null,
                    type: dataSource,
                });

                kendo.bind($("#province"), viewModel);

            </script>


            <div class="district" style="width: 30%; float: left;">Powiat: <input name="EventDistrict" type="text" class="k-input k-textbox"/></div>
            <div class="borough" style="width: 30%; float: left;">Gmina: <input name="EventBorough" type="text" class="k-input k-textbox"/></div>
        </li>
        <li style="clear: both;"></li>
        <li>
            <label for="simple-input">Dane o sprawie</label>
            <div id="unit" style="width: 30%; float: left;"> Jednostka:
                <select data-role="dropdownlist"
                        data-option-label="Wybierz jednostkę"
                        data-value-primitive="true"
                        data-filter="contains"
                        data-value-field="Id"
                        data-text-field="Name"
                        name="Unit"
                        data-bind="value: UnitID, source: type"
                        >
                </select>
            </div>
            <script>

                var dataSource = new kendo.data.DataSource({
                    transport: {
                        read: {
                            type: "POST",
                            url: "https://192.168.4.14/lead/get_unit",
                            dataType: "json"
                        }
                    }
                });

                var viewModel = kendo.observable({
                    Unit: null,
                    type: dataSource,
                });

                kendo.bind($("#unit"), viewModel);
            </script>

            <div id="basket" style="width: 30%; float: left;">Koszyk:
                <select data-role="dropdownlist"
                        data-option-label="Wybierz koszyk"
                        data-value-primitive="true"
                        data-filter="contains"
                        data-value-field="Id"
                        data-text-field="Name"
                        name="Basket"
                        data-bind="value: BasketID, source: type"
                        >
                </select>
            </div>
            <script>

                var dataSource = new kendo.data.DataSource({
                    transport: {
                        read: {
                            type: "POST",
                            url: "https://192.168.4.14/lead/get_basket",
                            dataType: "json"
                        }
                    }
                });

                var viewModel = kendo.observable({
                    Basket: null,
                    type: dataSource,
                });

                kendo.bind($("#basket"), viewModel);
            </script>
            <div id="event_type" style="width: 30%; float: left;">Typ zdarzenia:
                 <select data-role="dropdownlist"
                        data-option-label="Wybierz typ"
                        data-value-primitive="true"
                        data-filter="contains"
                        data-value-field="Id"
                        data-text-field="Name"
                        name="EventType"
                        data-bind="value: EventTypeID, source: type"
                        >
                    </select>
            </div>
            <script>

                var dataSource = new kendo.data.DataSource({
                    transport: {
                        read: {
                            type: "POST",
                            url: "https://192.168.4.14/lead/get_event_type",
                            dataType: "json"
                        }
                    }
                });

                var viewModel = kendo.observable({
                    EventType: null,
                    type: dataSource,
                });

                kendo.bind($("#event_type"), viewModel);
                </script>
        </li>
        <li style="clear: both;"></li>
        <li>
            Ilość umów: <input name="Contract" type="text" class="k-input k-textbox" style="width: 20%;"/>
            <div id="status" style="width: 30%; float: left;">Status:
                <select data-role="dropdownlist"
                    data-option-label="Wybierz status"
                    data-value-primitive="true"
                    data-filter="contains"
                    data-value-field="Id"
                    data-text-field="Name"
                    name="Status"
                    data-bind="value: StatusID, source: type"
                    >
                </select>
            </div>
            <script>

                var dataSource = new kendo.data.DataSource({
                    transport: {
                        read: {
                            type: "POST",
                            url: "https://192.168.4.14/lead/get_status",
                            dataType: "json"
                        }
                    }
                });

                var viewModel = kendo.observable({
                    Status: null,
                    type: dataSource,
                });

                kendo.bind($("#status"), viewModel);
             </script>
        </li>
        <li>
            <label for="simple-textarea">Opis</label>
            <textarea id="simple-textarea" name="Description" class="k-textbox" style="width: 100%;" ></textarea>
        </li>
        <li>
            <label for="simple-textarea">Komentarze</label>
            Data komentarza: <input name="CommentDate" type="text" class="k-input k-textbox" style="width: 20%;"/>
            Komentarz <textarea id="simple-textarea" name="Comment" class="k-textbox" style="width: 60%;" ></textarea>
        </li>
    </ul>

</script>

<div id="grid"></div>
<?php require_once($_SERVER ['DOCUMENT_ROOT'].'/footer.php'); ?>

