(function (a) {
    
    let scriptEle = document.createElement("script");
    scriptEle.setAttribute("src", "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.js");
    document.body.appendChild(scriptEle);
    scriptEle.addEventListener("load", () => {
        a(function () {
            function z(b) {
              (dtodMonth = a(".dtod_woo_delivery_offdays_another_month_" + b)
                .find("select")
                .val()),
                dtodMonth != "" &&
                  a(
                    ".dtod_woo_delivery_offdays_another_month_" +
                      b +
                      " .dtod_woo_delivery_offdays_add_another_month"
                  )
                    .children("input")
                    .removeAttr("disabled"),
                a("select[name='dtod_woo_delivery_offdays_month_" + b + "[]']").on(
                  "change",
                  function (c) {
                    c.preventDefault(),
                      a(this)
                        .removeAttr("name")
                        .attr("name", "dtod_woo_delivery_offdays_month_" + b + "[]"),
                      a(this)
                        .siblings("input")
                        .removeAttr("name")
                        .attr(
                          "name",
                          "dtod_woo_delivery_offdays_dates_" + a(this).val() + "_" + b
                        )
                        .removeAttr("disabled");
                  }
                );
            }
            function l() {
              a("#dtod_woo_delivery_meta_box_time_field option").each(function () {
                var b = a(this).attr("disabled");
                typeof b !== typeof undefined &&
                  b !== !1 &&
                  (a(this).show(), a(this).attr("disabled", !1));
                var c = a(this).text();
                var d = c.indexOf(x);
                d !== -1 && a(this).text(c.substr(0, d));
              });
            }
            function m(c, d, b) {
              c == d &&
                a("#dtod_woo_delivery_meta_box_time_field option").each(function () {
                  if (a(this).val() != "") {
                    var c = a(this).val().split(" - ");
                    (_times_one = c[0].split(":")),
                      (_times_two = c[1].split(":")),
                      (c =
                        _times_one[0] * 60 +
                        parseInt(_times_one[1]) +
                        " - " +
                        (_times_two[0] * 60 + parseInt(_times_two[1]))),
                      (c = c.split(" - ")),
                      c[0] <= b && c[1] <= b && a(this).attr("disabled", !0);
                  }
                });
            }
            function n(d, e) {
              var b = {};
              if (d.length > 0) {
                for (var c = 0; d.length > c; c++) b[d[c]] = (b[d[c]] || 0) + 1;
                for (var f in b) {
                  if (!b.hasOwnProperty(f)) continue;
                  typeof e !== typeof undefined &&
                    e !== !1 &&
                    b[f] >= e &&
                    e != 0 &&
                    a("#dtod_woo_delivery_meta_box_time_field option").each(
                      function () {
                        a(this).val() == f &&
                          (a(this).attr("disabled", !0),
                          a(this).text(a(this).text() + x));
                      }
                    );
                }
              }
            }
            function o(c, d, e, b) {
              c == d &&
                e &&
                a("#dtod_woo_delivery_meta_box_time_field option").each(function () {
                  if (a(this).val() != "") {
                    var c = a(this).val().split(" - ");
                    (_times_one = c[0].split(":")),
                      (_times_two = c[1].split(":")),
                      (c =
                        _times_one[0] * 60 +
                        parseInt(_times_one[1]) +
                        " - " +
                        (_times_two[0] * 60 + parseInt(_times_two[1]))),
                      (c = c.split(" - ")),
                      c[0] <= b && c[1] > b && a(this).attr("disabled", !0);
                  }
                });
            }
            function p(b) {
              ((typeof a("#dtod_woo_delivery_meta_box_datepicker").val() ==
                typeof undefined &&
                a("#dtod_woo_delivery_meta_box_datepicker").val() == 0) ||
                a("#dtod_woo_delivery_meta_box_datepicker").val() == "") &&
                a("#dtod_woo_delivery_meta_box_time_field option").each(function () {
                  if (a(this).val() != "") {
                    var c = a(this).val().split(" - ");
                    (_times_one = c[0].split(":")),
                      (_times_two = c[1].split(":")),
                      (c =
                        _times_one[0] * 60 +
                        parseInt(_times_one[1]) +
                        " - " +
                        (_times_two[0] * 60 + parseInt(_times_two[1]))),
                      (c = c.split(" - ")),
                      c[0] <= b && c[1] <= b && a(this).attr("disabled", !0);
                  }
                });
            }
            function q() {
              a("#dtod_woo_delivery_meta_box_pickup_field option").each(function () {
                var b = a(this).attr("disabled");
                typeof b !== typeof undefined &&
                  b !== !1 &&
                  (a(this).show(), a(this).attr("disabled", !1));
                var c = a(this).text();
                var d = c.indexOf(y);
                d !== -1 && a(this).text(c.substr(0, d));
              });
            }
            function r(c, d, b) {
              c == d &&
                a("#dtod_woo_delivery_meta_box_pickup_field option").each(function () {
                  if (a(this).val() != "") {
                    var c = a(this).val().split(" - ");
                    (_pickupTimes_one = c[0].split(":")),
                      (_pickupTimes_two = c[1].split(":")),
                      (c =
                        _pickupTimes_one[0] * 60 +
                        parseInt(_pickupTimes_one[1]) +
                        " - " +
                        (_pickupTimes_two[0] * 60 + parseInt(_pickupTimes_two[1]))),
                      (c = c.split(" - ")),
                      c[0] <= b && c[1] <= b && a(this).attr("disabled", !0);
                  }
                });
            }
            function s(d, e) {
              var b = {};
              if (d.length > 0) {
                for (var c = 0; d.length > c; c++) b[d[c]] = (b[d[c]] || 0) + 1;
                for (var f in b) {
                  if (!b.hasOwnProperty(f)) continue;
                  typeof e !== typeof undefined &&
                    e !== !1 &&
                    b[f] >= e &&
                    e != 0 &&
                    a("#dtod_woo_delivery_meta_box_pickup_field option").each(
                      function () {
                        a(this).val() == f &&
                          (a(this).attr("disabled", !0),
                          a(this).text(a(this).text() + y));
                      }
                    );
                }
              }
            }
            function t(c, d, e, b) {
              c == d &&
                e &&
                a("#dtod_woo_delivery_meta_box_pickup_field option").each(function () {
                  if (a(this).val() != "") {
                    var c = a(this).val().split(" - ");
                    (_times_one = c[0].split(":")),
                      (_times_two = c[1].split(":")),
                      (c =
                        _times_one[0] * 60 +
                        parseInt(_times_one[1]) +
                        " - " +
                        (_times_two[0] * 60 + parseInt(_times_two[1]))),
                      (c = c.split(" - ")),
                      c[0] <= b && c[1] > b && a(this).attr("disabled", !0);
                  }
                });
            }
            function u(b) {
              ((typeof a("#dtod_woo_delivery_meta_box_pickup_datepicker").val() ==
                typeof undefined &&
                a("#dtod_woo_delivery_meta_box_pickup_datepicker").val() == 0) ||
                a("#dtod_woo_delivery_meta_box_pickup_datepicker").val() == "") &&
                a("#dtod_woo_delivery_meta_box_pickup_field option").each(function () {
                  if (a(this).val() != "") {
                    var c = a(this).val().split(" - ");
                    (_pickupTimes_one = c[0].split(":")),
                      (_pickupTimes_two = c[1].split(":")),
                      (c =
                        _pickupTimes_one[0] * 60 +
                        parseInt(_pickupTimes_one[1]) +
                        " - " +
                        (_pickupTimes_two[0] * 60 + parseInt(_pickupTimes_two[1]))),
                      (c = c.split(" - ")),
                      c[0] <= b && c[1] <= b && a(this).attr("disabled", !0);
                  }
                });
            }
            function v() {
              var j = [];
              var k = a("#dtod_woo_delivery_meta_box_datepicker").data(
                "selectable_dates"
              );
              all_disable_week_days = c;
              for (var d = 0; d < k; d++) {
                var q = new Date();
                var r = q.setDate(q.getDate() + d);
                var b = new Date(r);
                var s = "0" + (Number(b.getMonth()) + 1);
                var t = "0" + b.getDate();
                var u = b.getDay().toString();
                var e = b.getFullYear() + "-" + s.substr(-2) + "-" + t.substr(-2);
                a.inArray(e, f) === -1 &&
                a.inArray(e, h) === -1 &&
                a.inArray(u, all_disable_week_days) === -1
                  ? j.push(e)
                  : (k += 1);
              }
              var g = a("#dtod_woo_delivery_meta_box_order_id").val();
              var i = a(
                "#dtod_woo_delivery_meta_box_delivery_selection_field option:selected"
              ).val();
              a("#dtod_woo_delivery_meta_box_datepicker").length
                ? a("#dtod_woo_delivery_meta_box_datepicker").flatpickr({
                    enable: j,
                    minDate: today_date,
                    dateFormat: A,
                    locale: { firstDayOfWeek: B },
                    onChange: function (f, h, j) {
                      a(".dtod-woo-delivery-loading-image").fadeIn();
                      var b = new Date(f);
                      var d = "0" + (b.getMonth() + 1);
                      var e = "0" + b.getDate();
                      var c = b.getFullYear() + "-" + d.substr(-2) + "-" + e.substr(-2);
                      a.ajax({
                        url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                        type: "POST",
                        data: {
                          _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                          action: "dtod_woo_delivery_meta_box_get_orders",
                          date: c,
                          orderId: g,
                          deliveryType: i,
                        },
                        success: function (f) {
                          data = JSON.parse(f.data);
                          var b = data.current_time;
                          var d = data.delivery_times;
                          var e = data.max_order_per_slot;
                          l(),
                            m(c, today_date, b),
                            n(d, e),
                            o(c, today_date, data.disabled_current_time_slot, b),
                            p(b),
                            a(".dtod-woo-delivery-loading-image").fadeOut();
                        },
                      });
                    },
                    onReady: function (h, j, d) {
                      a(".dtod-woo-delivery-loading-image").fadeIn();
                      var c = new Date(d.selectedDates[0]);
                      var e = "0" + (c.getMonth() + 1);
                      var f = "0" + c.getDate();
                      if (d.selectedDates.length > 0)
                        var b =
                          c.getFullYear() + "-" + e.substr(-2) + "-" + f.substr(-2);
                      else var b = today_date;
                      a.ajax({
                        url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                        type: "POST",
                        data: {
                          _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                          action: "dtod_woo_delivery_meta_box_get_orders",
                          date: b,
                          orderId: g,
                          deliveryType: i,
                        },
                        success: function (f) {
                          data = JSON.parse(f.data);
                          var c = data.current_time;
                          var d = data.delivery_times;
                          var e = data.max_order_per_slot;
                          l(),
                            m(b, today_date, c),
                            n(d, e),
                            o(b, today_date, data.disabled_current_time_slot, c),
                            p(c),
                            a(".dtod-woo-delivery-loading-image").fadeOut();
                        },
                      });
                    },
                  })
                : a("#dtod_woo_delivery_meta_box_time_field").length
                ? (a(".dtod-woo-delivery-loading-image").fadeIn(),
                  a.ajax({
                    url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                    type: "POST",
                    data: {
                      _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                      action: "dtod_woo_delivery_meta_box_get_orders",
                      onlyDeliveryTime: !0,
                      date: today_date,
                      orderId: g,
                      deliveryType: i,
                    },
                    success: function (e) {
                      data = JSON.parse(e.data);
                      var b = data.current_time;
                      if (Object.keys(data).length) {
                        var c = data.delivery_times;
                        var d = data.max_order_per_slot;
                      } else {
                        var c = [];
                        var d = [];
                      }
                      l(),
                        m(today_date, today_date, b),
                        n(c, d),
                        o(today_date, today_date, data.disabled_current_time_slot, b),
                        p(b),
                        a(".dtod-woo-delivery-loading-image").fadeOut();
                    },
                  }))
                : a(".dtod-woo-delivery-loading-image").fadeOut();
            }
            function w() {
              var h = [];
              var j = a("#dtod_woo_delivery_meta_box_pickup_datepicker").data(
                "pickup_selectable_dates"
              );
              all_pickup_disable_week_days = d;
              for (var c = 0; c < j; c++) {
                var k = new Date();
                var l = k.setDate(k.getDate() + c);
                var b = new Date(l);
                var m = "0" + (Number(b.getMonth()) + 1);
                var n = "0" + b.getDate();
                var o = b.getDay().toString();
                var e = b.getFullYear() + "-" + m.substr(-2) + "-" + n.substr(-2);
                a.inArray(e, g) === -1 &&
                a.inArray(et, i) === -1 &&
                a.inArray(o, all_pickup_disable_week_days) === -1
                  ? h.push(e)
                  : (j += 1);
              }
              var f = a("#dtod_woo_delivery_meta_box_order_id").val();
              (pickupDate = a("#dtod_woo_delivery_meta_box_pickup_datepicker").val()),
                a("#dtod_woo_delivery_meta_box_pickup_datepicker").length
                  ? a("#dtod_woo_delivery_meta_box_pickup_datepicker").flatpickr({
                      enable: h,
                      minDate: today_date,
                      dateFormat: C,
                      locale: { firstDayOfWeek: D },
                      onChange: function (g, h, i) {
                        a(".dtod-woo-delivery-loading-image").fadeIn(),
                          a("#dtod_woo_delivery_meta_box_pickup_field").val("");
                        var b = new Date(g);
                        var d = "0" + (b.getMonth() + 1);
                        var e = "0" + b.getDate();
                        var c =
                          b.getFullYear() + "-" + d.substr(-2) + "-" + e.substr(-2);
                        a.ajax({
                          url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                          type: "POST",
                          data: {
                            _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                            action: "dtod_woo_delivery_meta_box_get_orders_pickup",
                            date: c,
                            orderId: f,
                          },
                          success: function (f) {
                            data = JSON.parse(f.data);
                            var b = data.current_time;
                            formatedPickupDateSelected =
                              data.formated_pickup_date_selected;
                            var d = data.pickup_delivery_times;
                            var e = data.pickup_max_order_per_slot;
                            q(),
                              r(c, today_date, b),
                              s(d, e),
                              t(
                                c,
                                today_date,
                                data.pickup_disabled_current_time_slot,
                                b
                              ),
                              u(b),
                              a(".dtod-woo-delivery-loading-image").fadeOut();
                          },
                        });
                      },
                      onReady: function (h, i, d) {
                        a(".dtod-woo-delivery-loading-image").fadeIn();
                        var c = new Date(d.selectedDates[0]);
                        var e = "0" + (c.getMonth() + 1);
                        var g = "0" + c.getDate();
                        if (d.selectedDates.length > 0)
                          var b =
                            c.getFullYear() + "-" + e.substr(-2) + "-" + g.substr(-2);
                        else var b = today_date;
                        a.ajax({
                          url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                          type: "POST",
                          data: {
                            _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                            action: "dtod_woo_delivery_meta_box_get_orders_pickup",
                            date: b,
                            orderId: f,
                          },
                          success: function (f) {
                            data = JSON.parse(f.data);
                            var c = data.current_time;
                            formatedPickupDateSelected =
                              data.formated_pickup_date_selected;
                            var d = data.pickup_delivery_times;
                            var e = data.pickup_max_order_per_slot;
                            q(),
                              r(b, today_date, c),
                              s(d, e),
                              t(
                                b,
                                today_date,
                                data.pickup_disabled_current_time_slot,
                                c
                              ),
                              u(c),
                              a(".dtod-woo-delivery-loading-image").fadeOut();
                          },
                        });
                      },
                    })
                  : a("#dtod_woo_delivery_meta_box_pickup_field").length
                  ? (a(".dtod-woo-delivery-loading-image").fadeIn(),
                    a.ajax({
                      url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                      type: "POST",
                      data: {
                        _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                        action: "dtod_woo_delivery_meta_box_get_orders_pickup",
                        onlyPickupTime: !0,
                        date: today_date,
                        orderId: f,
                      },
                      success: function (e) {
                        data = JSON.parse(e.data);
                        var b = data.current_time;
                        if (Object.keys(data).length) {
                          var c = data.pickup_delivery_times;
                          var d = data.pickup_max_order_per_slot;
                        } else {
                          var c = [];
                          var d = [];
                        }
                        q(),
                          r(today_date, today_date, b),
                          s(c, d),
                          t(
                            today_date,
                            today_date,
                            data.pickup_disabled_current_time_slot,
                            b
                          ),
                          u(b),
                          a(".dtod-woo-delivery-loading-image").fadeOut();
                      },
                    }))
                  : a(".dtod-woo-delivery-loading-image").fadeOut();
            }
            a("select[name='dtod_delivery_time_timezone']").select2({
              dropdownCssClass: "dtod_delivery_time_timezone_dropdown",
            }),
              "wooDeliveryFreeDateTimeActiveIndex" in localStorage
                ? (a(
                    ".dtod-woo-delivery-free-vertical-tabs .dtod-woo-delivery-free-tabs"
                  )
                    .find(
                      "> button:eq(" +
                        localStorage.getItem("wooDeliveryFreeDateTimeActiveIndex") +
                        ")"
                    )
                    .addClass("dtod-woo-delivery-active"),
                  a(
                    ".dtod-woo-delivery-free-vertical-tabs .dtod-woo-delivery-maincontent"
                  )
                    .find(
                      "> .dtod-woo-delivery-tabcontent:eq(" +
                        localStorage.getItem("wooDeliveryFreeDateTimeActiveIndex") +
                        ")"
                    )
                    .addClass("dtod-woo-delivery-active"))
                : (a(
                    ".dtod-woo-delivery-free-vertical-tabs .dtod-woo-delivery-free-tabs"
                  )
                    .find("> button:eq(0)")
                    .addClass("dtod-woo-delivery-active"),
                  a(
                    ".dtod-woo-delivery-free-vertical-tabs .dtod-woo-delivery-maincontent"
                  )
                    .find("> .dtod-woo-delivery-tabcontent:eq(0)")
                    .addClass("dtod-woo-delivery-active")),
              a(document).on(
                "click",
                ".dtod-woo-delivery-free-vertical-tabs .dtod-woo-delivery-free-tabs button",
                function (i) {
                  var c = a(this).index();
                  localStorage.setItem("wooDeliveryFreeDateTimeActiveIndex", c);
                  var b = a(this).data("tab");
                  var d = this.getAttribute("data-tab");
                  var e = a(
                    '.dtod-woo-delivery-free-vertical-tabs .dtod-woo-delivery-maincontent .dtod-woo-delivery-tabcontent[data-tab="' +
                      b +
                      '"]'
                  );
                  var f = document.querySelector(
                    '.dtod-woo-delivery-free-vertical-tabs .dtod-woo-delivery-maincontent .dtod-woo-delivery-tabcontent[data-tab="' +
                      d +
                      '"]'
                  );
                  var g = document.querySelector(
                    ".dtod-woo-delivery-free-vertical-tabs .dtod-woo-delivery-maincontent .dtod-woo-delivery-tabcontent.dtod-woo-delivery-active"
                  );
                  a(
                    ".dtod-woo-delivery-free-vertical-tabs .dtod-woo-delivery-free-tabs button"
                  ).removeClass("dtod-woo-delivery-active"),
                    a(
                      '.dtod-woo-delivery-free-vertical-tabs .dtod-woo-delivery-free-tabs button[data-tab="' +
                        b +
                        '"]'
                    ).addClass("dtod-woo-delivery-active");
                  var h = anime({
                    duration: 400,
                    targets: g,
                    opacity: [1, 0],
                    translateX: [0, "100%"],
                    easing: "easeInOutCubic",
                    complete: function () {
                      a(
                        ".dtod-woo-delivery-free-vertical-tabs .dtod-woo-delivery-maincontent .dtod-woo-delivery-tabcontent"
                      ).removeClass("dtod-woo-delivery-active"),
                        e.addClass("dtod-woo-delivery-active");
                      var b = anime({
                        duration: 400,
                        targets: f,
                        opacity: [0, 1],
                        translateX: ["100%", "0"],
                        easing: "easeInOutCubic",
                      });
                    },
                  });
                }
              ),
              a(document).on(
                "submit",
                "#dtod_delivery_timezone_form_submit",
                function (c) {
                  c.preventDefault();
                  var b = a(this).serialize();
                  a("input[name='dtod_delivery_timezone_form_submit']").val(
                    "Updating ..."
                  ),
                    a.ajax({
                      url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                      type: "post",
                      data: {
                        _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                        action: "dtod_woo_delivery_process_delivery_timezone_form",
                        dateFormData: b,
                      },
                      success: function (b) {
                        b.success &&
                          (a("input[name='dtod_delivery_timezone_form_submit']").val(
                            "Save Changes"
                          ),
                          a(".dtod-woo-delivery-timezone-tab-notice")
                            .html(
                              "<span class='dashicons dashicons-yes'></span> Settings Changed Successfully"
                            )
                            .css("background", "#249D60")
                            .show("slide", { direction: "right" }),
                          setTimeout(function () {
                            a(".dtod-woo-delivery-timezone-tab-notice").hide("slide", {
                              direction: "right",
                            });
                          }, 4000));
                      },
                    });
                }
              ),
              a(document).on(
                "click",
                ".dtod_woo_delivery_enable_dynamic_order_type",
                function (a) {
                  a.preventDefault(),
                    alert("This feature only available on PRO version");
                }
              ),
              a(document).on(
                "submit",
                "#dtod_delivery_delivery_option_form_submit",
                function (c) {
                  c.preventDefault();
                  var b = a(this).serialize();
                  a("input[name='dtod_delivery_delivery_option_form_submit']").val(
                    "Updating ..."
                  ),
                    a.ajax({
                      url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                      type: "post",
                      data: {
                        _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                        action: "dtod_woo_delivery_process_delivery_option_settings",
                        formData: b,
                      },
                      success: function (b) {
                        b.success &&
                          (a(
                            "input[name='dtod_delivery_delivery_option_form_submit']"
                          ).val("Save Changes"),
                          a(".dtod-woo-delivery-delivery-option-notice").show("slide", {
                            direction: "right",
                          }),
                          setTimeout(function () {
                            a(".dtod-woo-delivery-delivery-option-notice").hide(
                              "slide",
                              { direction: "right" }
                            );
                          }, 4000));
                      },
                    });
                }
              ),
              a(document).on("submit", "#dtod_delivery_date_form_submit", function (c) {
                c.preventDefault();
                var b = a(this).serialize();
                a("input[name='dtod_delivery_date_form_submit']").val("Updating ..."),
                  a.ajax({
                    url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                    type: "post",
                    data: {
                      _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                      action: "dtod_woo_delivery_process_delivery_date_form",
                      dateFormData: b,
                    },
                    success: function (b) {
                      b.success &&
                        (a("input[name='dtod_delivery_date_form_submit']").val(
                          "Save Changes"
                        ),
                        a(".dtod-woo-delivery-date-tab-notice").show("slide", {
                          direction: "right",
                        }),
                        setTimeout(function () {
                          a(".dtod-woo-delivery-date-tab-notice").hide("slide", {
                            direction: "right",
                          });
                        }, 4000));
                    },
                  });
              }),
              a(document).on(
                "submit",
                "#dtod_delivery_pickup_date_form_submit",
                function (c) {
                  c.preventDefault();
                  var b = a(this).serialize();
                  a("input[name='dtod_delivery_pickup_date_form_submit']").val(
                    "Updating ..."
                  ),
                    a.ajax({
                      url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                      type: "post",
                      data: {
                        _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                        action: "dtod_woo_delivery_process_pickup_date_form",
                        dateFormData: b,
                      },
                      success: function (b) {
                        b.success &&
                          (a("input[name='dtod_delivery_pickup_date_form_submit']").val(
                            "Save Changes"
                          ),
                          a(".dtod-woo-delivery-pickup-date-tab-notice").show("slide", {
                            direction: "right",
                          }),
                          setTimeout(function () {
                            a(".dtod-woo-delivery-pickup-date-tab-notice").hide(
                              "slide",
                              { direction: "right" }
                            );
                          }, 4000));
                      },
                    });
                }
              ),
              a(document).on(
                "submit",
                "#dtod_delivery_date_offdays_form_submit",
                function (c) {
                  c.preventDefault();
                  var b = a(this).serialize();
                  a("input[name='dtod_delivery_date_offdays_form_submit']").val(
                    "Updating ..."
                  ),
                    a.ajax({
                      url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                      type: "post",
                      data: {
                        _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                        action: "dtod_woo_delivery_process_offdays_form",
                        dateFormData: b,
                      },
                      success: function (b) {
                        b.success &&
                          (a(
                            "input[name='dtod_delivery_date_offdays_form_submit']"
                          ).val("Save Changes"),
                          a(".dtod-woo-delivery-date-tab-offdays-notice")
                            .html(
                              "<span class='dashicons dashicons-yes'></span> Settings Changed Successfully"
                            )
                            .css("background", "#249D60")
                            .show("slide", { direction: "right" }),
                          setTimeout(function () {
                            a(".dtod-woo-delivery-date-tab-offdays-notice").hide(
                              "slide",
                              { direction: "right" }
                            );
                          }, 4000));
                      },
                    });
                }
              ),
              a(document).on("submit", "#dtod_delivery_time_form_submit", function (c) {
                c.preventDefault();
                var b = a(this).serialize();
                a("input[name='dtod_delivery_time_form_submit']").val("Updating ..."),
                  a.ajax({
                    url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                    type: "post",
                    data: {
                      _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                      action: "dtod_woo_delivery_process_delivery_time_form",
                      dateFormData: b,
                    },
                    success: function (b) {
                      b.success &&
                        (a("input[name='dtod_delivery_time_form_submit']").val(
                          "Save Changes"
                        ),
                        a(".dtod-woo-delivery-time-tab-notice")
                          .html(
                            "<span class='dashicons dashicons-yes'></span> Settings Changed Successfully"
                          )
                          .css("background", "#249D60")
                          .show("slide", { direction: "right" }),
                        setTimeout(function () {
                          a(".dtod-woo-delivery-time-tab-notice").hide("slide", {
                            direction: "right",
                          });
                        }, 4000));
                    },
                  });
              }),
              a(document).on("submit", "#dtod_pickup_time_form_submit", function (i) {
                i.preventDefault(),
                  a(".dtod_pickup_end_time_greater_notice").css("display", "none");
                var b = a("input[name='dtod_pickup_time_slot_starts_hour']").val();
                var e = a("input[name='dtod_pickup_time_slot_starts_min']").val();
                var g = a("select[name='dtod_pickup_time_slot_starts_format']").val();
                g == "am"
                  ? ((b = b == "12" ? "0" : b),
                    (deliveryStart = Number(b) * 60 + Number(e)))
                  : ((b = b == "12" ? "0" : b),
                    (deliveryStart = (Number(b) + 12) * 60 + Number(e)));
                var c = a("input[name='dtod_pickup_time_slot_ends_hour']").val();
                var d = a("input[name='dtod_pickup_time_slot_ends_min']").val();
                var f = a("select[name='dtod_pickup_time_slot_ends_format']").val();
                if (
                  (f == "am"
                    ? ((end12Hour = c == "12" ? "0" : c),
                      (deliveryEnd = Number(end12Hour) * 60 + Number(d)))
                    : ((c = c == "12" ? "0" : c),
                      (deliveryEnd = (Number(c) + 12) * 60 + Number(d))),
                  f == "am" &&
                    c == "12" &&
                    (d == "0" || d == "00") &&
                    (deliveryEnd = 1440),
                  deliveryEnd <= deliveryStart)
                ) {
                  a(".dtod_pickup_end_time_greater_notice").css(
                    "display",
                    "inline-flex"
                  ),
                    a("input[name='dtod_pickup_time_form_submit']")
                      .val("Error!")
                      .removeClass("dtod-woo-delivery-submit-btn")
                      .addClass("dtod-woo-delivery-pickup-submit-btn-error"),
                    setTimeout(function () {
                      a("input[name='dtod_pickup_time_form_submit']")
                        .val("Save Changes")
                        .removeClass("dtod-woo-delivery-pickup-submit-btn-error")
                        .addClass("dtod-woo-delivery-submit-btn");
                    }, 4000);
                  return;
                }
                var h = a(this).serialize();
                a("input[name='dtod_pickup_time_form_submit']")
                  .val("Updating ...")
                  .removeClass("dtod-woo-delivery-pickup-submit-btn-error")
                  .addClass("dtod-woo-delivery-submit-btn"),
                  a.ajax({
                    url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                    type: "post",
                    data: {
                      _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                      action: "dtod_woo_delivery_process_pickup_time_form",
                      dateFormData: h,
                    },
                    success: function (b) {
                      b.success &&
                        (a("input[name='dtod_pickup_time_form_submit']").val(
                          "Save Changes"
                        ),
                        a(".dtod-woo-delivery-pickup-time-tab-notice")
                          .html(
                            "<span class='dashicons dashicons-yes'></span> Settings Changed Successfully"
                          )
                          .css("background", "#249D60")
                          .show("slide", { direction: "right" }),
                        setTimeout(function () {
                          a(".dtod-woo-delivery-pickup-time-tab-notice").hide("slide", {
                            direction: "right",
                          });
                        }, 4000));
                    },
                  });
              });
            var b = "";
            a(document).on(
              "change keypress input",
              ".dtod_woo_delivery_offdays_year",
              function (c) {
                if (c.which != 8 && c.which != 0 && (c.which < 48 || c.which > 57))
                  return (
                    a(".dtod-woo-delivery-date-tab-offdays-notice")
                      .html(
                        '<span class="dashicons dashicons-no-alt"></span> No character allowed except number'
                      )
                      .css("background", "#DD4F43")
                      .show("slide", { direction: "right" }),
                    setTimeout(function () {
                      a(".dtod-woo-delivery-date-tab-offdays-notice").hide("slide", {
                        direction: "right",
                      });
                    }, 4000),
                    !1
                  );
                if (a(this).val().length == "4") {
                  var d = a(this)
                    .parents()
                    .siblings(".dtod-woo-delivery-add-year-html")
                    .children("input")
                    .map(function () {
                      return this.value;
                    })
                    .get();
                  (b = a(this).val()),
                    jQuery.inArray(b, d) != "-1"
                      ? (a(this).val(""),
                        a(".dtod-woo-delivery-date-tab-offdays-notice")
                          .html(
                            '<span class="dashicons dashicons-no-alt"></span> You have already enter the Year'
                          )
                          .css("background", "#DD4F43")
                          .show("slide", { direction: "right" }),
                        setTimeout(function () {
                          a(".dtod-woo-delivery-date-tab-offdays-notice").hide(
                            "slide",
                            { direction: "right" }
                          );
                        }, 4000))
                      : b < new Date().getFullYear()
                      ? (a(this).val(""),
                        a(".dtod-woo-delivery-date-tab-offdays-notice")
                          .html(
                            '<span class="dashicons dashicons-no-alt"></span> You have enter a outdated year'
                          )
                          .css("background", "#DD4F43")
                          .show("slide", { direction: "right" }),
                        setTimeout(function () {
                          a(".dtod-woo-delivery-date-tab-offdays-notice").hide(
                            "slide",
                            { direction: "right" }
                          );
                        }, 4000))
                      : (a(this).attr("name", "dtod_woo_delivery_offdays_year_" + b),
                        a(this)
                          .parent()
                          .children(".dtod_woo_delivery_offdays_another_month")
                          .addClass("dtod_woo_delivery_offdays_another_month_" + b),
                        a(this)
                          .parent()
                          .children(".dtod_woo_delivery_offdays_another_month_" + b)
                          .find("select")
                          .attr("name", "dtod_woo_delivery_offdays_month_" + b + "[]")
                          .removeAttr("disabled"),
                        a(this)
                          .parent()
                          .children("span")
                          .find(".dtod-woo-delivery-add-month-btn")
                          .removeAttr("disabled"),
                        z(b));
                } else
                  a(this).removeAttr("name"),
                    (selector = a(this)
                      .parent()
                      .children(".dtod_woo_delivery_offdays_another_month_" + b)),
                    selector
                      .find(".dtod-woo-delivery-select-field")
                      .attr("disabled", "disabled"),
                    selector
                      .find(".dtod-woo-delivery-input-field")
                      .attr("disabled", "disabled"),
                    selector
                      .find(".dtod-woo-delivery-add-month-btn")
                      .attr("disabled", "disabled"),
                    selector
                      .removeAttr("class")
                      .addClass("dtod_woo_delivery_offdays_another_month");
              }
            ),
              a(document).on("click", ".dtod-woo-delivery-dummy-btn", function () {
                var c = a(this)
                  .prev(".dtod-woo-delivery-add-month-btn")
                  .attr("disabled");
                typeof c !== typeof undefined && c !== !1
                  ? (a(".dtod-woo-delivery-date-tab-offdays-notice")
                      .html(
                        '<span class="dashicons dashicons-no-alt"></span> Input the Year First'
                      )
                      .css("background", "#DD4F43")
                      .show("slide", { direction: "right" }),
                    setTimeout(function () {
                      a(".dtod-woo-delivery-date-tab-offdays-notice").hide("slide", {
                        direction: "right",
                      });
                    }, 4000))
                  : ((b = a(this).parent().siblings("input").val()),
                    a(this)
                      .parent()
                      .siblings(".dtod_woo_delivery_offdays_another_month_" + b)
                      .children(".dtod_woo_delivery_offdays_add_another_month")
                      .first()
                      .clone()
                      .appendTo(".dtod_woo_delivery_offdays_another_month_" + b),
                    a(this)
                      .parent()
                      .siblings(".dtod_woo_delivery_offdays_another_month_" + b)
                      .children(".dtod_woo_delivery_offdays_add_another_month")
                      .last()
                      .children("select")
                      .val(""),
                    a(this)
                      .parent()
                      .siblings(".dtod_woo_delivery_offdays_another_month_" + b)
                      .children(".dtod_woo_delivery_offdays_add_another_month")
                      .last()
                      .children("input")
                      .val("")
                      .removeAttr("name"),
                    a(this)
                      .parent()
                      .siblings(".dtod_woo_delivery_offdays_another_month_" + b)
                      .children(".dtod_woo_delivery_offdays_add_another_month")
                      .last()
                      .append(
                        "<button class='dtod-offdays-month-remove'><span class='dashicons dashicons-trash'></span></button>"
                      ),
                    z(b));
              }),
              a(document).on("click", ".dtod-offdays-month-remove", function (b) {
                b.preventDefault(), a(this).parent().remove();
              }),
              (addYearHTML = a(".dtod-woo-delivery-add-year-html").first().clone()),
              a(document).on("click", ".dtod-woo-delivery-add-year-btn", function (b) {
                b.preventDefault(),
                  a(".dtod-woo-delivery-offdays").append(addYearHTML.clone()),
                  a(".dtod-woo-delivery-add-year-html")
                    .last()
                    .prepend(
                      "<button class='dtod-offdays-year-remove'><span class='dashicons dashicons-trash'></span></button>"
                    ),
                  a(".dtod-woo-delivery-add-year-html")
                    .last()
                    .children(".dtod-arrow")
                    .remove(),
                  a(".dtod-woo-delivery-add-year-html")
                    .last()
                    .children(".dtod_woo_delivery_offdays_year")
                    .removeAttr("name")
                    .val(""),
                  (length = a(".dtod-woo-delivery-add-year-html")
                    .last()
                    .children(".dtod_woo_delivery_offdays_another_month")
                    .children(".dtod_woo_delivery_offdays_add_another_month").length),
                  length > 1 &&
                    a(".dtod-woo-delivery-add-year-html")
                      .last()
                      .children(".dtod_woo_delivery_offdays_another_month")
                      .children(".dtod_woo_delivery_offdays_add_another_month:gt(0)")
                      .remove(),
                  a(".dtod-woo-delivery-add-year-html")
                    .last()
                    .children(".dtod_woo_delivery_offdays_another_month")
                    .removeAttr("class")
                    .addClass("dtod_woo_delivery_offdays_another_month"),
                  a(".dtod-woo-delivery-add-year-html")
                    .last()
                    .children(".dtod_woo_delivery_offdays_another_month")
                    .children(".dtod_woo_delivery_offdays_add_another_month")
                    .children("select")
                    .removeAttr("name")
                    .attr("disabled", "disabled")
                    .val(""),
                  a(".dtod-woo-delivery-add-year-html")
                    .last()
                    .children(".dtod_woo_delivery_offdays_another_month")
                    .children(".dtod_woo_delivery_offdays_add_another_month")
                    .children("input")
                    .removeAttr("name")
                    .attr("disabled", "disabled")
                    .val("");
              }),
              a(document).on("click", ".dtod-offdays-year-remove", function (b) {
                b.preventDefault(), a(this).parent().remove();
              }),
              a(document).on(
                "submit",
                "#dtod_delivery_localization_settings_form_submit",
                function (c) {
                  c.preventDefault();
                  var b = a(this).serialize();
                  a(
                    "input[name='dtod_delivery_localization_settings_form_submit']"
                  ).val("Updating ..."),
                    a.ajax({
                      url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                      type: "post",
                      data: {
                        _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                        action: "dtod_woo_delivery_process_localization_settings",
                        formData: b,
                      },
                      success: function (b) {
                        b.success &&
                          (a(
                            "input[name='dtod_delivery_localization_settings_form_submit']"
                          ).val("Save Changes"),
                          a(".dtod-woo-delivery-localization-settings-notice").show(
                            "slide",
                            { direction: "right" }
                          ),
                          setTimeout(function () {
                            a(".dtod-woo-delivery-localization-settings-notice").hide(
                              "slide",
                              { direction: "right" }
                            );
                          }, 4000));
                      },
                    });
                }
              ),
              a(document).on(
                "submit",
                "#dtod_delivery_other_settings_form_submit",
                function (c) {
                    console.log("UPDATING OTHER SETTINGS")
                  c.preventDefault();
                  var b = a(this).serialize();
                  a("input[name='dtod_delivery_other_settings_form_submit']").val(
                    "Updating ..."
                  ),
                    a.ajax({
                      url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                      type: "post",
                      data: {
                        _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                        action: "dtod_woo_delivery_process_other_settings",
                        dateFormData: b,
                      },
                      success: function (b) {
                        b.success &&
                          (a(
                            "input[name='dtod_delivery_other_settings_form_submit']"
                          ).val("Save Changes"),
                          a(".dtod-woo-delivery-other-settings-notice").show("slide", {
                            direction: "right",
                          }),
                          setTimeout(function () {
                            a(".dtod-woo-delivery-other-settings-notice").hide(
                              "slide",
                              { direction: "right" }
                            );
                          }, 4000));
                      },
                    });
                }
              ),
              a(document).on("submit", "#dtod_delivery_prime_submit", function (c) {
                c.preventDefault();
                var b = a(this).serialize();
                a("input[name='dtod_delivery_prime_submit']").val("Updating ..."),
                  a.ajax({
                    url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                    type: "post",
                    data: {
                      _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                      action: "dtod_woo_setup_prime_settings",
                      dateFormData: b,
                    },
                    success: function (b) {
                      b.success &&
                        (a("input[name='dtod_delivery_prime_submit']").val(
                          "Save Changes"
                        ),
                        a(".dtod-woo-delivery-prime-notice")
                          .html(
                            "<span class='dashicons dashicons-yes'></span> Settings Changed Successfully"
                          )
                          .css("background", "#249D60")
                          .show("slide", { direction: "right" }),
                        setTimeout(function () {
                          a(".dtod-woo-delivery-prime-notice").hide("slide", {
                            direction: "right",
                          });
                        }, 4000));
                    },
                  });
              }),
              a(document).on(
                "click",
                ".dtod-woo-delivery-review-notice ul li a",
                function (c) {
                  c.preventDefault();
                  let b = a(this).attr("val");
                  b == "given" &&
                    window.open(
                      "https://wordpress.org/support/plugin/woo-delivery/reviews/?rate=5#new-post",
                      "_blank"
                    ),
                    a(".dtod-woo-delivery-review-notice").slideUp(200, "linear"),
                    a.ajax({
                      url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                      type: "post",
                      data: {
                        _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                        action: "dtod_woo_delivery_save_review_notice",
                        notice: b,
                      },
                      success: function (a) {},
                    });
                }
              ),
              a(document).on(
                "click",
                ".dtod-woo-delivery-metabox-update-btn",
                function (i) {
                  i.preventDefault();
                  var b = a(this).children("button").text();
                  b == "Update"
                    ? a(this).children("button").text("Updating...")
                    : a(this)
                        .children("button")
                        .text(b + "ing...");
                  var c = a(
                    "input[name='dtod_woo_delivery_meta_box_datepicker']"
                  ).val();
                  var d = a(
                    "input[name='dtod_woo_delivery_meta_box_pickup_datepicker']"
                  ).val();
                  var e = a(
                    "select[name='dtod_woo_delivery_meta_box_delivery_selection_field'] option:selected"
                  ).val();
                  var f = a(
                    "select[name='dtod_woo_delivery_meta_box_time_field']"
                  ).val();
                  var g = a(
                    "select[name='dtod_woo_delivery_meta_box_pickup_field']"
                  ).val();
                  var h = a("#dtod_woo_delivery_meta_box_order_id").val();
                  a.ajax({
                    url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                    type: "post",
                    data: {
                      _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                      action: "dtod_woo_delivery_save_meta_box_information",
                      deliveryOption: e,
                      date: c,
                      pickupDate: d,
                      time: f,
                      pickupTime: g,
                      orderId: h,
                    },
                    success: function (a) {
                      location.reload();
                    },
                  });
                }
              );
            var e = "";
            (e += '<div class="dtod-woo-delivery-loading-image">'),
              (e += '<div class="dtod-woo-delivery-loading-gif">'),
              (e +=
                '<img src="' +
                a(".dtod-woo-delivery-metabox-update-section").data("plugin-url") +
                'public/images/loading.gif" alt="" />'),
              (e += "</div>"),
              (e += "</div>"),
              a("#dtod_woo_delivery_meta_box").append(e),
              (today_date = a("#dtod_woo_delivery_admin_setting_wrapper").data(
                "today_date"
              ));
            var A = a("#dtod_woo_delivery_meta_box_datepicker").data("date_format");
            if (
              typeof a("#dtod_woo_delivery_meta_box_datepicker").data(
                "disable_week_days"
              ) !== typeof undefined &&
              a("#dtod_woo_delivery_meta_box_datepicker").data("disable_week_days") !==
                !1
            ) {
              var c = a("#dtod_woo_delivery_meta_box_datepicker").data(
                "disable_week_days"
              );
              (c = c.toString()), (c = c.split(","));
            } else var c = [];
            var B = a("#dtod_woo_delivery_meta_box_datepicker").data(
              "week_starts_from"
            );
            if (
              typeof a("#dtod_woo_delivery_meta_box_datepicker").data(
                "disable_dates"
              ) !== typeof undefined &&
              a("#dtod_woo_delivery_meta_box_datepicker").data("disable_dates") !== !1
            ) {
              var f = a("#dtod_woo_delivery_meta_box_datepicker").data("disable_dates");
              (f = f.toString()), (f = f.split("::"));
            }
            var C = a("#dtod_woo_delivery_meta_box_pickup_datepicker").data(
              "pickup_date_format"
            );
            if (
              typeof a("#dtod_woo_delivery_meta_box_pickup_datepicker").data(
                "pickup_disable_week_days"
              ) !== typeof undefined &&
              a("#dtod_woo_delivery_meta_box_pickup_datepicker").data(
                "pickup_disable_week_days"
              ) !== !1
            ) {
              var d = a("#dtod_woo_delivery_meta_box_pickup_datepicker").data(
                "pickup_disable_week_days"
              );
              (d = d.toString()), (d = d.split(","));
            } else var d = [];
            var D = a("#dtod_woo_delivery_meta_box_pickup_datepicker").data(
              "pickup_week_starts_from"
            );
            if (
              typeof a("#dtod_woo_delivery_meta_box_pickup_datepicker").data(
                "pickup_disable_dates"
              ) !== typeof undefined &&
              a("#dtod_woo_delivery_meta_box_pickup_datepicker").data(
                "pickup_disable_dates"
              ) !== !1
            ) {
              var g = a("#dtod_woo_delivery_meta_box_pickup_datepicker").data(
                "pickup_disable_dates"
              );
              (g = g.toString()), (g = g.split("::"));
            }
            var x = a("#dtod_woo_delivery_meta_box_time_field").data(
              "order_limit_notice"
            );
            var y = a("#dtod_woo_delivery_meta_box_pickup_field").data(
              "pickup_limit_notice"
            );
            var h = [];
            var i = [];
            if (
              a("#dtod_woo_delivery_meta_box_datepicker").length ||
              a("#dtod_woo_delivery_meta_box_pickup_datepicker").length
            ) {
              a(".dtod-woo-delivery-loading-image").fadeIn();
              var j = a(
                "#dtod_woo_delivery_meta_box_delivery_selection_field option:selected"
              ).val();
              a.when(
                a.ajax({
                  url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                  type: "POST",
                  data: {
                    _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                    action: "dtod_woo_delivery_admin_disable_max_delivery_pickup_date",
                    deliverySelector: j,
                  },
                  success: function (a) {
                    (data = JSON.parse(a.data)),
                      (h = data.disable_delivery_date_passed_time),
                      (i = data.disable_pickup_date_passed_time);
                  },
                })
              ).then(function (a) {
                j == "delivery" ? v() : j == "pickup" && w();
              });
            } else w(), v();
            if (
              (a(
                "#dtod_woo_delivery_meta_box_delivery_selection_field option:selected"
              ).val() == "delivery"
                ? (a("#dtod_woo_delivery_meta_box_datepicker").hide(),
                  a("#dtod_woo_delivery_meta_box_time_field").hide(),
                  a("#dtod_woo_delivery_meta_box_pickup_datepicker").hide(),
                  a("#dtod_woo_delivery_meta_box_pickup_field").hide(),
                  a("#dtod_woo_delivery_meta_box_datepicker").show(),
                  a("#dtod_woo_delivery_meta_box_time_field").show())
                : a(
                    "#dtod_woo_delivery_meta_box_delivery_selection_field option:selected"
                  ).val() == "pickup" &&
                  (a("#dtod_woo_delivery_meta_box_pickup_datepicker").hide(),
                  a("#dtod_woo_delivery_meta_box_pickup_field").hide(),
                  a("#dtod_woo_delivery_meta_box_datepicker").hide(),
                  a("#dtod_woo_delivery_meta_box_time_field").hide(),
                  a("#dtod_woo_delivery_meta_box_pickup_datepicker").show(),
                  a("#dtod_woo_delivery_meta_box_pickup_field").show()),
              a(document).on(
                "change",
                "#dtod_woo_delivery_meta_box_delivery_selection_field",
                function (c) {
                  c.preventDefault(), a(".dtod-woo-delivery-loading-image").fadeIn();
                  var b = a(this).val();
                  a.when(
                    a.ajax({
                      url: dtod_woo_delivery_ajax_obj.dtod_woo_delivery_ajax_url,
                      type: "POST",
                      data: {
                        _ajax_nonce: dtod_woo_delivery_ajax_obj.nonce,
                        action:
                          "dtod_woo_delivery_admin_disable_max_delivery_pickup_date",
                        deliverySelector: b,
                      },
                      success: function (a) {
                        (data = JSON.parse(a.data)),
                          (disableMaxDeliveryDate =
                            data.disable_for_max_delivery_dates),
                          (disableMaxPickupDate = data.disable_for_max_pickup_dates),
                          (h = data.disable_delivery_date_passed_time),
                          (i = data.disable_pickup_date_passed_time);
                      },
                    })
                  ).then(function (c) {
                    b == "delivery"
                      ? (a("#dtod_woo_delivery_meta_box_datepicker").hide(),
                        a("#dtod_woo_delivery_meta_box_time_field").hide(),
                        a("#dtod_woo_delivery_meta_box_pickup_datepicker").hide(),
                        a("#dtod_woo_delivery_meta_box_pickup_field").hide(),
                        a("#dtod_woo_delivery_meta_box_datepicker").show(),
                        a("#dtod_woo_delivery_meta_box_time_field").show(),
                        v())
                      : b == "pickup" &&
                        (a("#dtod_woo_delivery_meta_box_pickup_datepicker").hide(),
                        a("#dtod_woo_delivery_meta_box_pickup_field").hide(),
                        a("#dtod_woo_delivery_meta_box_datepicker").hide(),
                        a("#dtod_woo_delivery_meta_box_time_field").hide(),
                        a("#dtod_woo_delivery_meta_box_pickup_datepicker").show(),
                        a("#dtod_woo_delivery_meta_box_pickup_field").show(),
                        w());
                  });
                }
              ),
              a("#dtod_woo_delivery_code_editor_css").length)
            ) {
              var k = wp.codeEditor.defaultSettings
                ? _.clone(wp.codeEditor.defaultSettings)
                : {};
              k.codemirror = _.extend({}, k.codemirror, {
                indentUnit: 2,
                tabSize: 2,
                mode: "css",
                lineNumbers: !1,
              });
              var E = wp.codeEditor.initialize(
                a("#dtod_woo_delivery_code_editor_css"),
                k
              );
            }
          });
    });
  
})(jQuery);
