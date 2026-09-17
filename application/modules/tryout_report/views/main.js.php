<script type="text/javascript">
    $(document).ready(function() {

        var _section = "tryout_report";
        var _table = "table-tryout-report";

        // Inisialisasi DataTables
        var table_tryout_report = $("#" + _table).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo base_url('tryout_report/ajax_get_leaderboard/') ?>",
                type: "post",
                data: function(d) {
                    d.tryout_id = $("#filter-tryout").val();
                    d.session_id = $("#filter-session").val();
                }
            },
            columns: [{
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: "nama_lengkap"
                },
                {
                    data: "title"
                },
                {
                    data: "name"
                },
                {
                    data: "total_score"
                },
                {
                    data: "status"
                },
                {
                    data: "start_time"
                },
                {
                    data: "end_time"
                }
            ],
            columnDefs: [{
                    targets: 0,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        var rank = meta.row + meta.settings._iDisplayStart + 1;
                        var rankClass = rank == 1 ? 'rank-1' : (rank == 2 ? 'rank-2' : (rank == 3 ? 'rank-3' : 'rank-other'));
                        return '<span class="rank-badge ' + rankClass + '">' + rank + '</span>';
                    }
                },
                {
                    targets: 4,
                    className: 'text-center',
                    render: function(data) {
                        return '<strong>' + (data != null ? data : '-') + '</strong>';
                    }
                },
                {
                    targets: 5,
                    className: 'text-center',
                    render: function(data) {
                        return '<span class="badge bg-success">' + (data || '-') + '</span>';
                    }
                }
            ],
            autoWidth: false,
            pageLength: 25,
            order: [
                [4, 'desc']
            ],
            language: {
                searchPlaceholder: "Cari...",
                sProcessing: '<div style="text-align: center;"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>'
            },
            sDom: '<"dataTables_ct"><"dataTables__top"fb>rt<"dataTables__bottom"ip><"clear">',
            initComplete: function() {
                $(this).closest(".dataTables_wrapper").find(".dataTables__top").prepend(
                    '<div class="dataTables_buttons hidden-sm-down actions">' +
                    '<span class="actions__item zmdi zmdi-refresh" data-table-action="reload" title="Reload" />' +
                    '</div>'
                );
            }
        });

        $(".dataTables_filter input[type=search]").focus(function() {
            $(this).closest(".dataTables_filter").addClass("dataTables_filter--toggled")
        });

        $(".dataTables_filter input[type=search]").blur(function() {
            $(this).closest(".dataTables_filter").removeClass("dataTables_filter--toggled")
        });

        $("body").on("click", "[data-table-action]", function(a) {
            a.preventDefault();
            var b = $(this).data("table-action");
            if ("reload" === b) {
                $("#" + _table).DataTable().ajax.reload(null, false);
            };
        });

        // Filter perubahan
        $("#filter-tryout").on("change", function() {
            var tryout_id = $(this).val();
            var $session = $("#filter-session");
            $session.html('<option value="">-- Semua Sesi --</option>');

            if (tryout_id) {
                $.ajax({
                    url: "<?php echo base_url('tryout_report/ajax_get_sessions_by_tryout/') ?>" + tryout_id,
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        if (response.status && response.data) {
                            $.each(response.data, function(i, s) {
                                $session.append('<option value="' + s.id + '">' + s.name + '</option>');
                            });
                        }
                    }
                });
            }

            table_tryout_report.ajax.reload();
            loadSummary();
        });

        $("#filter-session").on("change", function() {
            table_tryout_report.ajax.reload();
            loadSummary();
        });

        // Muat ringkasan statistik
        function loadSummary() {
            $.ajax({
                url: "<?php echo base_url('tryout_report/ajax_get_summary') ?>",
                type: "post",
                data: {
                    tryout_id: $("#filter-tryout").val(),
                    session_id: $("#filter-session").val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $("#stat-participants").text(response.data.total_participants);
                        $("#stat-attempts").text(response.data.total_attempts);
                        $("#stat-avg").text(response.data.average_score);
                        $("#stat-high").text(response.data.highest_score);
                    }
                }
            });
        }

        // Export Excel
        $("#" + _section).on("click", "." + _section + "-action-export-excel", function(e) {
            e.preventDefault();
            var params = new URLSearchParams();
            var tid = $("#filter-tryout").val();
            var sid = $("#filter-session").val();
            if (tid) params.append('tryout_id', tid);
            if (sid) params.append('session_id', sid);

            var url = "<?php echo base_url('tryout_report/export_excel') ?>";
            if (params.toString()) url += '?' + params.toString();
            window.location.href = url;
        });

        // Export CSV
        $("#" + _section).on("click", "." + _section + "-action-export-csv", function(e) {
            e.preventDefault();
            var params = new URLSearchParams();
            var tid = $("#filter-tryout").val();
            var sid = $("#filter-session").val();
            if (tid) params.append('tryout_id', tid);
            if (sid) params.append('session_id', sid);

            var url = "<?php echo base_url('tryout_report/export_csv') ?>";
            if (params.toString()) url += '?' + params.toString();
            window.location.href = url;
        });

        // Initial load summary
        loadSummary();
    });
</script>
