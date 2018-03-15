$(document).ready(function() {
    $(this).off('click','.report_filter');
    $(this).on('click','.report_filter',function(){
        $(this).toggleClass('zaznaczonyFiltr');
    });



    var allMonths = [

        {
            field: 'm1',
            title: 'Styczeń'
        },
        {
            field: 'm2',
            title: 'Luty'
        },
        {
            field: 'm3',
            title: 'Marzec'
        },
        {
            field: 'm4',
            title: 'Kwiecień'
        },
        {
            field: 'm5',
            title: 'Maj'
        },
        {
            field: 'm6',
            title: 'Czerwiec'
        },
        {
            field: 'm7',
            title: 'Lipiec'
        },
        {
            field: 'm8',
            title: 'Sierpień'
        },
        {
            field: 'm9',
            title: 'Wrzesień'
        },
        {
            field: 'm10',
            title: 'Październik'
        },
        {
            field: 'm11',
            title: 'Listopad'
        },
        {
            field: 'm12',
            title: 'Grudzień'
        }
    ];



    var months = {};
    months['m1'] = "Styczeń";
    months['m2'] = "Luty";
    months['m3'] = "Marzec";
    months['m4'] = "Kwiecień";
    months['m5'] = "Maj";
    months['m6'] = "Czerwiec";
    months['m7'] = "Lipiec";
    months['m8'] = "Sierpień";
    months['m9'] = "Wrzesień";
    months['m10'] = "Październik";
    months['m11'] = "Listopad";
    months['m12'] = "Grudzień";


    $('.generuj_raport').click(function(){
        $('#grid').empty();
        var status = '';
        var type = '';
        var year = '';
        var month = '';
        var bona = '';
        var personal = '';
        var managers = [
            {
                field: "ManagerName",
                title: "Imię i nazwisko"
            }
        ];
        var agents = [
            {
                field: "AgentName",
                title: "Imię i nazwiskodfd"
            }
        ];
        var wykres = [
            {
                categoryField: "CaseGroup"
            }
        ];

        var elements = ($('.report_filter'));
        var liczba_prm = elements.size();
        var user = $('.report_user').attr('id');
        for(var i=0;i<liczba_prm;i++) {
            for (var j = 0; j < elements[i].classList.length; j++) {
                if (elements[i].classList[j] == 'zaznaczonyFiltr') {
                    for (var x = 0; x < elements[i].classList.length; x++) {
                        if (elements[i].classList[x] == 'status') {
                            status = status.concat("'" + elements[i].textContent + "',");
                        }
                        if (elements[i].classList[x] == 'type') {
                            type = type.concat("'" + elements[i].textContent + "',");
                        }
                        if (elements[i].classList[x] == 'year') {
                            year = year.concat("" + elements[i].textContent + ",");
                        }
                        if (elements[i].classList[x] == 'month') {
                            month = month.concat("['" + elements[i].textContent + "'],");
                            var newElement = {};
                            newElement['field'] = ""+elements[i].textContent+"";
                            newElement['title'] = months[''+elements[i].textContent+''];
                            managers.push(newElement);
                            agents.push(newElement);


                            var newElementb = {};
                            newElementb['field'] = ""+elements[i].textContent+"";
                            newElementb['name'] = months[''+elements[i].textContent+''];
                            wykres.push(newElementb);


                        }
                        if (elements[i].classList[x] == 'bona') {
                            bona = bona.concat("'" + elements[i].textContent + "',");
                        }
                        if (elements[i].classList[x] == 'personal') {
                            personal = personal.concat("'" + elements[i].textContent + "',");
                        }
                    }
                }
            }
        }
        if(managers.length == 1){
            managers = [
                {
                    field: "ManagerName",
                    title: "Imię i nazwisko"
                }
            ]
            managers = managers.concat(allMonths);
        };
        if(agents.length == 1){

            agents = [
                {
                    field: "AgentName",
                    title: "Imię i nazwisko"
                }
            ]
            agents = agents.concat(allMonths);


        };
        status = status.slice(0, status.length-1);
        type = type.slice(0, type.length-1);
        year = year.slice(0, year.length-1);
        month = month.slice(0, month.length-1);
        bona = bona.slice(0, bona.length-1);
        personal = personal.slice(0, personal.length-1);

        var
            dataSource = new kendo.data.DataSource({

                transport: {
                    read:  {
                        url: "../ajax/directorReportGetManagers",
                        data: {
                            user: user,
                            status: status,
                            type: type,
                            year: year,
                            month: month,
                            bona: bona,
                            personal: personal
                        },
                        type: "post",
                        dataType: "json"
                    }
                }
            });


        $("#grid").kendoGrid({
            dataSource: dataSource,
            sortable: true,
            pageable: false,
            pageSize: 10,
            detailInit: detailInit,
            dataBound: function() {
                this.expandRow(this.tbody.find("tr.k-master-row").first());
            },
            columns: managers

        });


        function detailInit(e) {
            $("<div/>").appendTo(e.detailCell).kendoGrid({
                dataSource: {
                    transport: {
                        read:  {
                            url: "../ajax/directorReportGetAgents",
                            data: {
                                user: user,
                                status: status,
                                type: type,
                                year: year,
                                month: month,
                                bona: bona,
                                personal: personal,
                                managerid: e.data.ManagerId
                            },
                            type: "post",
                            dataType: "json"
                        }
                    },
                    serverFiltering: true,
                    filter: { field: "ManagerId", value: e.data.ManagerId }

                },
                scrollable: true,
                sortable: true,
                pageable: false,
                pageSize: 10,
                columns: agents

            });





            $("<div/>").appendTo(e.detailCell).kendoChart({
                dataSource: {
                    transport: {
                        read:  {
                            url: "../ajax/directorReportCaseGroupForChart",
                            data: {
                                user: user,
                                status: status,
                                type: type,
                                year: year,
                                month: month,
                                bona: bona,
                                personal: personal,
                                managerid: e.data.ManagerId
                            },
                            type: "post",
                            dataType: "json"
                        }
                    },
                    sort: {
                        field: "AgentName",
                        dir: "asc"
                    }

                },
                title: {
                    text: "Wykres"
                },
                legend: {
                    position: "top"
                },
                seriesDefaults: {
                    type: "column"
                },
                //series: wykres,
                series: [
                    {
                        field: "g0",
                        categoryField: "AgentName",
                        name: "Brak Koszyka"
                    },
                    {
                        field: "g1",
                        categoryField: "AgentName",
                        name: "Grupa 1"
                    },
                    {
                        field: "g2",
                        categoryField: "AgentName",
                        name: "Grupa 2"
                    },
                    {
                        field: "g3",
                        categoryField: "AgentName",
                        name: "Grupa 3"
                    },
                    {
                        field: "g4",
                        categoryField: "AgentName",
                        name: "Grupa 4"
                    }
                ],
                categoryAxis: {
                    labels: {
                        rotation: -90
                    },
                    majorGridLines: {
                        visible: false
                    }
                },
                valueAxis: {
                    labels: {
                        format: "N0"
                    }
                },
                tooltip: {
                    visible: true,
                    format: "N0"
                }

            });






        }






























    });
});

