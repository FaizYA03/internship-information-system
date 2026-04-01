// public/assets/js/course.js
(function () {
    "use strict";

    if (typeof window.jQuery === "undefined") {
        console.error(
            "course.js: jQuery not found. Load jQuery BEFORE this script.",
        );
        return;
    }
    var $ = window.jQuery;

    $(function () {
        var cfg = window.CourseFormConfig || {};
        cfg.studentsUrl =
            cfg.studentsUrl || "/sistem-akademik/get-students-by-jurusan";
        cfg.recommendationsUrl =
            cfg.recommendationsUrl ||
            "/sistem-akademik/course/get-recommendations";
        cfg.initialKelas = cfg.initialKelas || null;
        cfg.preselectSiswa = Array.isArray(cfg.preselectSiswa)
            ? cfg.preselectSiswa
            : [];
        cfg.initialHari = cfg.initialHari || "";
        cfg.currentCourseId =
            cfg.currentCourseId ||
            (document.getElementById("course-form") &&
                document.getElementById("course-form").dataset
                    .currentCourseId) ||
            null;

        // slot metadata from blade (selectable slots only)
        var slotIds = cfg.slotIds || []; // e.g. ['1','2','3','4','5',...]
        var slotDetails = cfg.slotDetails || {}; // { '1': {label,start,end}, ... }

        // helper: index of slot id in slotIds array
        function slotIndex(id) {
            return slotIds.indexOf(String(id));
        }

        var $selectSiswa = $("#siswa_ids");
        var $slotStart = $("#slot_start");
        var $slotEnd = $("#slot_end");
        var availableSlotsCache = null;

        // init select2 if available
        if ($.fn && $.fn.select2) {
            try {
                $selectSiswa.select2({
                    placeholder: "Pilih siswa...",
                    width: "100%",
                });
                $('.select2-ruangan').select2({
                    placeholder: "-- Pilih Ruangan --",
                    width: '100%',
                    allowClear: true
                });
            } catch (e) {
                console.warn("select2 init failed", e);
            }
        }

        // Handle ruangan / labor_id extraction
        $('#ruangan').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var laborId = selectedOption.data('labor-id') || '';
            $('#labor_id').val(laborId);
        });

        function setLoading(on) {
            if (on) $("#students-loading").removeClass("d-none");
            else $("#students-loading").addClass("d-none");
        }

        function clearStudentsSelect() {
            $selectSiswa.find("option").remove();
            $selectSiswa.val(null);
            if ($.fn && $.fn.select2) $selectSiswa.trigger("change");
        }

        function addOptionIfNotExists(value, text) {
            if (
                $selectSiswa.find('option[value="' + value + '"]').length === 0
            ) {
                var opt = new Option(text, value, false, false);
                $selectSiswa.append(opt);
            }
        }

        function loadStudentsByKelas(kelasId, preselectIds) {
            clearStudentsSelect();
            if (!kelasId) return;

            setLoading(true);
            $selectSiswa.prop("disabled", true);

            $.ajax({
                url: cfg.studentsUrl,
                type: "GET",
                data: { kelas_id: kelasId },
                dataType: "json",
                cache: false,
                timeout: 10000,
            })
                .done(function (res) {
                    setLoading(false);
                    $selectSiswa.prop("disabled", false);

                    if (
                        !res ||
                        res.success !== true ||
                        !Array.isArray(res.students)
                    ) {
                        console.error("Invalid students payload", res);
                        if (window.Swal)
                            Swal.fire(
                                "Error",
                                "Format data siswa tidak valid. Lihat console.",
                                "error",
                            );
                        return;
                    }

                    // append options
                    res.students.forEach(function (s) {
                        var nama =
                            s.user && s.user.nama
                                ? s.user.nama
                                : s.name || "Siswa " + s.id;
                        var label = nama + " (" + (s.nisn || "-") + ")";
                        addOptionIfNotExists(s.id, label);
                    });

                    // jika server menyarankan select_all dan tidak ada preselect spesifik => select all
                    if (
                        res.select_all === true &&
                        (!Array.isArray(preselectIds) ||
                            preselectIds.length === 0)
                    ) {
                        var allVals = $selectSiswa
                            .find("option")
                            .map(function () {
                                return this.value;
                            })
                            .get();
                        $selectSiswa.val(allVals);
                    } else if (
                        Array.isArray(preselectIds) &&
                        preselectIds.length
                    ) {
                        // gunakan preselect yang diberikan (mis. saat edit)
                        $selectSiswa.val(preselectIds);
                    } else {
                        // default behavior: kosongkan / atau pilih semua jika Anda mau
                        $selectSiswa.val([]); // jangan pilih satu-per-satu
                    }

                    if ($.fn && $.fn.select2) $selectSiswa.trigger("change");
                })
                .fail(function (xhr, status, err) {
                    setLoading(false);
                    $selectSiswa.prop("disabled", false);
                    console.error(
                        "AJAX error loading students",
                        status,
                        err,
                        xhr && xhr.responseText,
                    );
                    if (window.Swal)
                        Swal.fire(
                            "Error",
                            "Gagal memuat data siswa. Lihat console/network.",
                            "error",
                        );
                });
        }

        // change event kelas
        $("#kelas_id").on("change", function () {
            var kelasId = $(this).val();
            loadStudentsByKelas(kelasId, []);
        });

        // initial students load if editing
        if (cfg.initialKelas) {
            loadStudentsByKelas(cfg.initialKelas, cfg.preselectSiswa);
        }

        // --- SLOT / RECOMMENDATION LOGIC ---

        // rebuild slot_end options so only slots with index >= startIndex are enabled
        function rebuildSlotEndOptions(startId, allowedSlotsArray) {
            var startIdx = startId ? slotIndex(startId) : -1;

            $slotEnd.find("option").each(function () {
                var val = $(this).val();
                if (!val) return; // skip placeholder
                var idx = slotIndex(val);
                var enable = true;

                // if we have availableSlotsCache (from recommendations), restrict to that set
                if (Array.isArray(allowedSlotsArray)) {
                    enable = allowedSlotsArray.includes(String(val));
                }

                // additionally ensure end index >= start index (if start selected)
                if (startIdx >= 0 && idx < startIdx) enable = false;

                $(this).prop("disabled", !enable);
            });

            // if currently selected end is disabled -> clear it
            var curEnd = $slotEnd.val();
            if (curEnd) {
                var curDisabled = $slotEnd
                    .find('option[value="' + curEnd + '"]')
                    .prop("disabled");
                if (curDisabled) {
                    $slotEnd.val("").trigger("change");
                }
            }

            if ($.fn && $.fn.select2) $slotEnd.trigger("change.select2");
        }

        // update slot_start options based on availableSlots (if provided)
        function applyAvailableSlotsToSelects(availableSlots) {
            availableSlotsCache = Array.isArray(availableSlots)
                ? availableSlots.map(String)
                : null;

            $slotStart.find("option").each(function () {
                var val = $(this).val();
                if (!val) return;
                var enable = true;
                if (availableSlotsCache)
                    enable = availableSlotsCache.includes(String(val));
                $(this).prop("disabled", !enable);
            });
            $slotEnd.find("option").each(function () {
                var val = $(this).val();
                if (!val) return;
                var enable = true;
                if (availableSlotsCache)
                    enable = availableSlotsCache.includes(String(val));
                $(this).prop("disabled", !enable);
            });

            // if selected start or end became disabled, clear them
            var curStart = $slotStart.val();
            if (
                curStart &&
                $slotStart
                    .find('option[value="' + curStart + '"]')
                    .prop("disabled")
            ) {
                $slotStart.val("").trigger("change");
            }
            var curEnd = $slotEnd.val();
            if (
                curEnd &&
                $slotEnd.find('option[value="' + curEnd + '"]').prop("disabled")
            ) {
                $slotEnd.val("").trigger("change");
            }

            if ($.fn && $.fn.select2) {
                $slotStart.trigger("change.select2");
                $slotEnd.trigger("change.select2");
            }
        }

        // recommendations fetch -> also apply available slots to selects
        function fetchRecommendations() {
            var hari = $("#hari").val();
            var kelasId = $("#kelas_id").val();
            var mataPelajaranId = $("#mata_pelajaran_id").val();

            if (!hari) {
                $("#recommendations").empty();
                // reset available slots (allow all)
                applyAvailableSlotsToSelects(null);
                return;
            }

            $.ajax({
                url: cfg.recommendationsUrl,
                type: "GET",
                data: {
                    hari: hari,
                    kelas_id: kelasId,
                    mata_pelajaran_id: mataPelajaranId,
                    exclude_course_id: cfg.currentCourseId || null
                },
                dataType: "json",
            })
                .done(function (res) {
                    if (
                        res &&
                        res.success &&
                        Array.isArray(res.available_slots)
                    ) {
                        // available_slots is array of {id,label,start,end}
                        var ids = res.available_slots.map(function (x) {
                            return String(x.id);
                        });
                        applyAvailableSlotsToSelects(ids);
                        renderRecommendations(res.available_slots);
                    } else {
                        applyAvailableSlotsToSelects(null);
                        $("#recommendations").empty();
                    }
                })
                .fail(function (xhr, status, err) {
                    console.error(
                        "Recommendations fetch error",
                        status,
                        err,
                        xhr && xhr.responseText,
                    );
                    applyAvailableSlotsToSelects(null);
                    $("#recommendations").empty();
                });
        }

        // render recommendation buttons (click behaviour supports two-slot selection)
        function renderRecommendations(slots) {
            var $wrap = $("#recommendations");
            $wrap.empty();
            if (!slots.length) {
                $wrap.append(
                    '<small class="text-muted">Tidak ada slot kosong.</small>',
                );
                return;
            }

            slots.forEach(function (s) {
                var id = String(s.id);
                var $b = $(
                    '<button type="button" class="btn btn-sm btn-outline-secondary recommendation-btn me-1 mb-1"></button>',
                );
                $b.text(s.label + " (" + s.start + " - " + s.end + ")");
                $b.data("slot-id", id);

                $b.on("click", function () {
                    var clicked = String($(this).data("slot-id"));
                    var currentStart = $slotStart.val();
                    var currentEnd = $slotEnd.val();

                    // if no start -> set start
                    if (!currentStart) {
                        $slotStart.val(clicked).trigger("change");
                        markRecommendationButtons();
                        return;
                    }

                    // if start exists but end not set -> try set end if index >= start
                    var startIdx = slotIndex(currentStart);
                    var clickedIdx = slotIndex(clicked);
                    if (currentStart && !currentEnd) {
                        if (clickedIdx >= startIdx) {
                            $slotEnd.val(clicked).trigger("change");
                            markRecommendationButtons();
                            return;
                        } else {
                            // clicked is before start -> treat as new start (clear end)
                            $slotStart.val(clicked).trigger("change");
                            $slotEnd.val("").trigger("change");
                            markRecommendationButtons();
                            return;
                        }
                    }

                    // if both exist -> replace start with clicked (and clear end)
                    if (currentStart && currentEnd) {
                        $slotStart.val(clicked).trigger("change");
                        $slotEnd.val("").trigger("change");
                        markRecommendationButtons();
                        return;
                    }
                });

                $wrap.append($b);
            });

            markRecommendationButtons();
        }

        function renderConflictWarning(details) {
            var $box = $("#live-conflict-warning");
            $box.empty();

            if (
                !details ||
                (!details.ruangan?.length &&
                    !details.guru?.length &&
                    !details.kelas?.length)
            ) {
                return;
            }

            var $alert = $('<div class="alert alert-warning"></div>');
            $alert.append("<strong>Perhatian — Bentrok terdeteksi:</strong>");
            var $list = $('<ul class="mt-2 mb-0 small"></ul>');

            // gabungkan semua buckets (ruangan/guru/kelas) untuk ditampilkan
            var buckets = ["ruangan", "guru", "kelas"];
            buckets.forEach(function (bucket) {
                if (!details[bucket] || !details[bucket].length) return;
                details[bucket].forEach(function (c) {
                    var jamMulai = (c.jam_mulai || "").substring(0, 5) || "-";
                    var jamSelesai =
                        (c.jam_selesai || "").substring(0, 5) || "-";
                    var jur = c.jurusan ? " — " + c.jurusan : "";
                    var tahun = c.tahun_ajaran ? " / " + c.tahun_ajaran : "";
                    var mp = c.mata_pelajaran ? " — " + c.mata_pelajaran : "";
                    var ru = c.ruangan ? " — Ruangan: " + c.ruangan : "";
                    var li =
                        "<li><strong>" +
                        (c.kelas || "-") +
                        jur +
                        tahun +
                        "</strong>" +
                        mp +
                        " (" +
                        jamMulai +
                        " - " +
                        jamSelesai +
                        ")" +
                        ru +
                        "</li>";
                    $list.append(li);
                });
            });

            $alert.append($list);
            $box.append($alert);
        }

        // CSRF token for POST
        var csrfToken =
            document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content") ||
            $('meta[name="csrf-token"]').attr("content");

        function checkConflictsLive() {
            var conflictUrl =
                cfg.conflictUrl ||
                (window.CourseFormConfig &&
                    window.CourseFormConfig.conflictUrl) ||
                $("#course-form").data("conflict-url");
            if (!conflictUrl) return;

            // di bagian awal inisialisasi cfg:
            cfg.currentCourseId =
                cfg.currentCourseId ||
                (document.getElementById("course-form")
                    ? document.getElementById("course-form").dataset
                          .currentCourseId
                    : "") ||
                null;

            var hari = $("#hari").val();
            var ruangan = $("#ruangan").val();
            var kelasId = $("#kelas_id").val();
            var slotStart = $("#slot_start").val();
            var slotEnd = $("#slot_end").val();
            var mataPelId = $("#mata_pelajaran_id").val();

            // only check when minimal field set (hari + slot start/end + ruangan)
            if (!hari || !ruangan || !slotStart || !slotEnd) {
                // clear live warning if any
                $("#live-conflict-warning").empty();
                return;
            }

            // show small loader
            $("#live-conflict-warning").html(
                '<div class="text-muted small">Memeriksa bentrok ruangan...</div>',
            );

            $.ajax({
                url: conflictUrl,
                type: "POST",
                data: {
                    hari: hari,
                    ruangan: ruangan,
                    kelas_id: kelasId,
                    slot_start: slotStart,
                    slot_end: slotEnd,
                    mata_pelajaran_id: mataPelId,
                    exclude_course_id: cfg.currentCourseId || null,
                    _token: csrfToken,
                },
                dataType: "json",
            })
                .done(function (res) {
                    if (res && res.success) {
                        if (res.has_conflict) {
                            renderConflictWarning(res.conflict_details);
                        } else {
                            $("#live-conflict-warning").empty();
                        }
                    } else {
                        $("#live-conflict-warning").empty();
                    }
                })
                .fail(function (xhr) {
                    console.error(
                        "checkConflictsLive error",
                        xhr && xhr.responseText,
                    );
                    $("#live-conflict-warning").empty();
                });
        }

        // bind events
        $(
            "#ruangan, #hari, #slot_start, #slot_end, #kelas_id, #mata_pelajaran_id",
        ).on("change input", function () {
            checkConflictsLive();
        });

        // initial check on page load (if fields prefilled)
        if (
            $("#ruangan").val() &&
            $("#hari").val() &&
            $("#slot_start").val() &&
            $("#slot_end").val()
        ) {
            checkConflictsLive();
        }

        // visual: mark recommendation buttons corresponding to selected start/end
        function markRecommendationButtons() {
            var curStart = $slotStart.val();
            var curEnd = $slotEnd.val();

            $(".recommendation-btn").each(function () {
                var id = String($(this).data("slot-id"));
                $(this).removeClass(
                    "active-start active-end btn-primary text-white",
                );
                if (curStart && id === String(curStart)) {
                    $(this).addClass("active-start btn-primary text-white");
                }
                if (curEnd && id === String(curEnd)) {
                    $(this).addClass("active-end");
                    // if same as start, keep primary style
                    if (!$(this).hasClass("btn-primary"))
                        $(this).addClass("btn-outline-secondary");
                }
            });
        }

        // on slot_start change -> rebuild slot_end allowed choices (and mark buttons)
        $slotStart.on("change", function () {
            var startId = $(this).val();
            // if we have cached availableSlots -> pass it to filter valid endings
            var allowed = Array.isArray(availableSlotsCache)
                ? availableSlotsCache
                : null;
            rebuildSlotEndOptions(startId, allowed);
            markRecommendationButtons();
        });

        // on slot_end change -> ensure it is >= start; otherwise clear and warn
        $slotEnd.on("change", function () {
            var startId = $slotStart.val();
            var endId = $(this).val();
            if (startId && endId) {
                var si = slotIndex(startId);
                var ei = slotIndex(endId);
                if (ei < si) {
                    if (window.Swal) {
                        Swal.fire(
                            "Perhatian",
                            "Slot akhir harus setelah atau sama dengan slot awal.",
                            "warning",
                        );
                    } else {
                        alert(
                            "Slot akhir harus setelah atau sama dengan slot awal.",
                        );
                    }
                    $slotEnd.val("").trigger("change");
                }
            }
            markRecommendationButtons();
        });

        // wire recommendation triggers
        $("#hari").on("change", fetchRecommendations);
        $("#kelas_id, #mata_pelajaran_id").on("change", function () {
            if ($("#hari").val()) fetchRecommendations();
        });

        // initial hit for recommendations if editing/existing hari
        if (cfg.initialHari) {
            setTimeout(fetchRecommendations, 200);
        }
    });
})();
