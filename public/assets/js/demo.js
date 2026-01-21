    //////////////////Openpermissions/////////////////////////////
    function openPermissionModel(siteurl , id){
        $('#manage_permission').modal('toggle');
        $('#manage_permission').modal('show');
        $("#role_id").val(id);
        roleHasPermission(siteurl ,id);
    }

    ////////////////////// CHANGE & SAVE PERMISSION ///////////////////
    function roleHasPermission(siteurl, id){
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: siteurl,
            data: {
                id: id,
            },
            // method: 'POST', //Post method,
            // dataType: 'json',
            success: function(response){
                console.log(response)
                $('#permissiontable').html(response);
            }
        });
    }

    ////////////////////// CHANGE & SAVE PERMISSION ///////////////////
    function changePermission(siteurl,module_id,permission_name,unique_id){
        jQuery.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        const id = $("#role_id").val();
        if($("#checkbox"+unique_id).is(":checked")){
            var checked = 1;
        } else if($("#checkbox"+unique_id).is(":not(:checked)")){
            var checked = 0;
        }
        $.ajax({
            type: "POST",
            url: siteurl,
            data: {
                id: id,
                module_id: module_id,
                permission_name: permission_name,
                checked: checked,
            }
        });
    }

    ////////////////////////////////////Change Menu URL //////////////////////////////////
    // function changeUrl(siteurl) {
    //     var oldUrl = $("#old_url").val();
    //     var newUrl = $("#new_url").val();

    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });
    //     $.ajax({
    //         type: "POST",
    //         url: siteurl,
    //         data: {
    //             old_url: oldUrl,
    //             new_url: newUrl
    //         },
    //         success: function(response) {
    //             alert("URL changed successfully");
    //             location.reload();
    //         },
    //         error: function(xhr) {
    //             alert("An error occurred: " + xhr.responseText);
    //         }
    //     });
    // }

    function changeUrl(url) {
        let oldUrl = document.getElementById('old_url').value.trim();
        let newUrl = document.getElementById('new_url').value.trim();

        if (oldUrl === "" || newUrl === "") {
            alert("Both fields are required!");
            return;
        }

        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: $('input[name="_token"]').val(),
                old_url: oldUrl,
                new_url: newUrl
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    alert(Object.values(errors).join("\n"));
                } else {
                    alert(xhr.responseJSON.message || "An error occurred");
                }
            }
        });
    }

    ///////////////////// GenerateSlug Menu Template URL/////////////////
    function generateSlug() {
        var name = $('#module_name').val().trim().toLowerCase();
        var slug = name.replace(/\s+/g, '-');
            $('#permalink').val(slug);
    }
    $('#module_name').on('input', generateSlug);

    function menus(url){
        var menutitle =$("#module_name").val();
        var menutitlearray= menutitle.split(' ');
        var menutitlestring= menutitlearray.join('-');
            // alert(menutitlearray[0].toLowerCase());
        if(menutitlearray[0].toLowerCase() === 'manage'){
            $("#menudescription").val(url+'/'+menutitlestring.charAt(0).toLowerCase()+ menutitlestring.toLowerCase().slice(1));
        }else{
            $("#menudescription").val(url+'/manage-'+menutitlestring.charAt(0).toLowerCase()+ menutitlestring.slice(1));
        }
    }

    ///////////////////////Chartjs-Dashboard-Line//////////////////
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
        var gradientLight = ctx.createLinearGradient(0, 0, 0, 225);
        gradientLight.addColorStop(0, "rgba(215, 227, 244, 1)");
        gradientLight.addColorStop(1, "rgba(215, 227, 244, 0)");
        var gradientDark = ctx.createLinearGradient(0, 0, 0, 225);
        gradientDark.addColorStop(0, "rgba(51, 66, 84, 1)");
        gradientDark.addColorStop(1, "rgba(51, 66, 84, 0)");
        // Line chart
        new Chart(document.getElementById("chartjs-dashboard-line"), {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Sales ($)",
                    fill: true,
                    backgroundColor: window.theme.id === "light" ? gradientLight : gradientDark,
                    borderColor: window.theme.primary,
                    data: [
                        2115,
                        1562,
                        1584,
                        1892,
                        1587,
                        1923,
                        2566,
                        2448,
                        2805,
                        3438,
                        2917,
                        3327
                    ]
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    intersect: false
                },
                hover: {
                    intersect: true
                },
                plugins: {
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    xAxes: [{
                        reverse: true,
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            stepSize: 1000
                        },
                        display: true,
                        borderDash: [3, 3],
                        gridLines: {
                            color: "rgba(0,0,0,0.0)",
                            fontColor: "#fff"
                        }
                    }]
                }
            }
        });
    });

    ////////////////////////Chartjs-Dashboard-Pie/////////////////
    document.addEventListener("DOMContentLoaded", function() {
        new Chart(document.getElementById("chartjs-dashboard-pie"), {
            type: "pie",
            data: {
                labels: ["Chrome", "Firefox", "IE", "Other"],
                datasets: [{
                    data: [4306, 3801, 1689, 3251],
                    backgroundColor: [
                        window.theme.primary,
                        window.theme.warning,
                        window.theme.danger,
                        "#E8EAED"
                    ],
                    borderWidth: 5,
                    borderColor: window.theme.white
                }]
            },
            options: {
                responsive: !window.MSInputMethodContext,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                cutoutPercentage: 70
            }
        });
    });

    //////////////////////Chartjs-Dashboard-Bar///////////////////
    document.addEventListener("DOMContentLoaded", function() {
        new Chart(document.getElementById("chartjs-dashboard-bar"), {
            type: "bar",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "This year",
                    backgroundColor: window.theme.primary,
                    borderColor: window.theme.primary,
                    hoverBackgroundColor: window.theme.primary,
                    hoverBorderColor: window.theme.primary,
                    data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
                    barPercentage: .75,
                    categoryPercentage: .5
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false
                        },
                        stacked: false,
                        ticks: {
                            stepSize: 20
                        }
                    }],
                    xAxes: [{
                        stacked: false,
                        gridLines: {
                            color: "transparent"
                        }
                    }]
                }
            }
        });
    });

    ///////////////////// Datatables Buttons/////////////////
    // $(function () {
    //     $("#example1").DataTable({
    //         "responsive": true,
    //         "lengthChange": true,
    //         "autoWidth": false,
    //         "paging": true,
    //         "pageLength": 10,
    //         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
    //         "language": {
    //             "paginate": {
    //                 "previous": "Previous",
    //                 "next": "Next"
    //             }
    //         },
    //         "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
    //         "<'row'<'col-sm-12'tr>>" +
    //         "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    //         "initComplete": function () {
    //             var api = this.api();
    //             $(api.table().footer()).find('th').each(function (i) {
    //                 if (i < 3) {
    //                     var title = $(this).text();
    //                     $(this).html('<input type="text"/>');

    //                     $('input', this).on('keyup change', function () {
    //                         if (api.column(i).search() !== this.value) {
    //                             api.column(i).search(this.value).draw();
    //                         }
    //                     });
    //                 } else {
    //                     $(this).html('');
    //                 }
    //             });
    //         }
    //     }).buttons().container().addClass('mt-2').appendTo('#example1_wrapper .col-md-6:eq(0)');
    // });
    $(function () {
        var table = $("#kt_ecommerce_report_views_table").DataTable();
        table.columns().every(function (i) {
            if (i === 0 || i === 1 || i === 2) {
                var column = this;
                var title = $(column.footer()).text();

                $(column.footer()).html(
                    '<input type="text" class="footer-search" />'
                );
                $('input', column.footer()).on('keyup change clear', function () {
                    if (column.search() !== this.value) {
                        column.search(this.value).draw();
                    }
                });
            } else {
                $(this.footer()).html('');
            }
        });
    });

    //////////////////////////Choices.js  Flatpickr///////////////////////////
    document.addEventListener("DOMContentLoaded", function() {
        // Choices.js
        new Choices(document.querySelector(".choices-single"));
        // Flatpickr
        flatpickr(".flatpickr-minimum");
        flatpickr(".flatpickr-datetime", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
        flatpickr(".flatpickr-human", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
        flatpickr(".flatpickr-multiple", {
            mode: "multiple",
            dateFormat: "Y-m-d"
        });
        flatpickr(".flatpickr-range", {
            mode: "range",
            dateFormat: "Y-m-d"
        });
        flatpickr(".flatpickr-time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });
    });

    ///////////////////////////////Date Timepicker/////////////////////
    document.addEventListener("DOMContentLoaded", function() {
        var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
        var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
        document.getElementById("datetimepicker-dashboard").flatpickr({
            inline: true,
            prevArrow: "<span class=\"fas fa-chevron-left\" title=\"Previous month\"></span>",
            nextArrow: "<span class=\"fas fa-chevron-right\" title=\"Next month\"></span>",
            defaultDate: defaultDate
        });
    });

    ///////////////////////////Theme/////////////////////////
    document.addEventListener("DOMContentLoaded", function() {
        var markers = [{
                coords: [31.230391, 121.473701],
                name: "Shanghai"
            },
            {
                coords: [28.704060, 77.102493],
                name: "Delhi"
            },
            {
                coords: [6.524379, 3.379206],
                name: "Lagos"
            },
            {
                coords: [35.689487, 139.691711],
                name: "Tokyo"
            },
            {
                coords: [23.129110, 113.264381],
                name: "Guangzhou"
            },
            {
                coords: [40.7127837, -74.0059413],
                name: "New York"
            },
            {
                coords: [34.052235, -118.243683],
                name: "Los Angeles"
            },
            {
                coords: [41.878113, -87.629799],
                name: "Chicago"
            },
            {
                coords: [51.507351, -0.127758],
                name: "London"
            },
            {
                coords: [40.416775, -3.703790],
                name: "Madrid "
            }
        ];
        var map = new jsVectorMap({
            map: "world",
            selector: "#world_map",
            zoomButtons: true,
            markers: markers,
            markerStyle: {
                initial: {
                    r: 9,
                    stroke: window.theme.white,
                    strokeWidth: 7,
                    stokeOpacity: .4,
                    fill: window.theme.primary
                },
                hover: {
                    fill: window.theme.primary,
                    stroke: window.theme.primary
                }
            },
            regionStyle: {
                initial: {
                    fill: window.theme["gray-200"]
                }
            },
            zoomOnScroll: false
        });
        window.addEventListener("resize", () => {
            map.updateSize();
        });
        setTimeout(function() {
            map.updateSize();
        }, 250);
    });

    // DataTables with Column Search by Select Inputs
    document.addEventListener("DOMContentLoaded", function() {
        $("#datatables-column-search-text-inputs tfoot th").each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" style="width: 100px;"/>');
        });

        var table = $("#datatables-column-search-text-inputs").DataTable();

        // Apply the search
        table.columns().every(function() {
            var that = this;
            $("input", this.footer()).on("keyup change clear", function() {
                if (that.search() !== this.value) {
                    that
                        .search(this.value)
                        .draw();
                }
            });
        });
    });

    // DataTables with Column Search by Select Inputs
    document.addEventListener("DOMContentLoaded", function() {
        $("#datatables-column-search-select-inputs").DataTable({
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var select = $('<select class="form-control form-control-sm"><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on("change", function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search(val ? "^" + val + "$" : "", true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });
    });

    //////////////////////////////////Pop State/////////////////////
    document.addEventListener("DOMContentLoaded", function(event) {
        setTimeout(function(){
        if(localStorage.getItem('popState') !== 'shown'){
            window.notyf.open({
            type: "success",
            message: "Get access to all 500+ components and 45+ pages with AdminKit PRO. <u><a class=\"text-white\" href=\"https://adminkit.io/pricing\" target=\"_blank\">More info</a></u> 🚀",
            duration: 10000,
            ripple: true,
            dismissible: false,
            position: {
                x: "left",
                y: "bottom"
            }
            });

            localStorage.setItem('popState','shown');
        }
        }, 15000);
    });

    ///////////////// employee_id validation //////////////
    function validateEmployeeId() {
        const input = document.getElementById('employee_id');
        const error = document.getElementById('employee_id_error');
        const regex = /^\d*$/;

        if (regex.test(input.value)) {
            error.style.display = 'none';
        } else {
            error.style.display = 'block';
            input.value = input.value.replace(/\D/g, '');
            setTimeout(() => {
                error.style.display = 'none';
            }, 1500);
        }
    }

    ///////////////// email validation //////////////
    document.getElementById('email').addEventListener('input', function () {
        let email = this.value;
        let errorSpan = document.getElementById('email-error');
        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email === "") {
            errorSpan.textContent = "Email is required";
            this.classList.add("is-invalid");
        } else if (!regex.test(email)) {
            errorSpan.textContent = "Please enter a valid email address";
            this.classList.add("is-invalid");
        } else {
            errorSpan.textContent = "";
            this.classList.remove("is-invalid");
        }
    });

    ///////////////// phone_number validation //////////////
    document.getElementById('phone_number').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');

        if (this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }

        let phone = this.value;
        let error = document.getElementById('phone_error');
        let regex = /^03\d{9}$/;

        if (!regex.test(phone)) {
            error.textContent = "Phone must be exactly 11 digits and start with 03";
            this.classList.add("is-invalid");
        } else {
            error.textContent = "";
            this.classList.remove("is-invalid");
        }
    });

    //////////// Toggle for Password ///////////////////
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        this.querySelector('i').classList.toggle('ki-eye');
        this.querySelector('i').classList.toggle('ki-eye-slash');
    });

    ///////////////////// Toggle for Confirm Password ///////////////////
    document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('confirmPassword');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        this.querySelector('i').classList.toggle('ki-eye');
        this.querySelector('i').classList.toggle('ki-eye-slash');
    });




