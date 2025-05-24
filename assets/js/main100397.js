var typingTimer;
var doneTypingInterval = 500;
jQuery.event.special.touchstart = {
  setup: function (_, ns, handle) {
    this.addEventListener("touchstart", handle, {
      passive: !ns.includes("noPreventDefault")
    });
  }
};
var myLazyLoad = new LazyLoad({
  elements_selector: ".lazy"
});
var vis = (function () {
  var stateKey, eventKey, keys = {
    hidden: "visibilitychange",
    webkitHidden: "webkitvisibilitychange",
    mozHidden: "mozvisibilitychange",
    msHidden: "msvisibilitychange"
  };
  for (stateKey in keys) {
    if (stateKey in document) {
      eventKey = keys[stateKey];
      break;
    }
  }
  return function (c) {
    if (c) document.addEventListener(eventKey, c);
    return !document[stateKey];
  }
})();

function toggleDiv(id) {
  $('#' + id).slideToggle('fast');
}

function showResult(e) {
  if (e.keyCode == 38 || e.keyCode == 40) {} else {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(function () {
      showResultDo();
    }, 500);
  }
}

function showResultDo() {
  var str = $('#s_top').val();
  if (str.length > 1) {
    $.get("/action/livesearch/livesearch.php", {
      q: str,
      iwear: $('#s_top').data('wearing-perfume-id')
    }, function (data) {
      $('#livesearch').html(data);
      if (data) {
        $('#livesearch').slideDown(100);
      } else {
        $('#livesearch').hide();
      }
    });
  } else {
    $('#livesearch').hide();
  }
}

function showResultMobile(e) {
  if (e.keyCode == 38 || e.keyCode == 40) {} else {
    clearTimeout(typingTimer);
    if ($('#s_top_m').val) {
      typingTimer = setTimeout(showResultDoMobile, doneTypingInterval);
    }
  }
}

function showResultDoMobile() {
  var str = $('#s_top_m').val();
  $.get("/action/livesearch/livesearch.php", {
    q: str,
    iwear: $('#s_top').data('wearing-perfume-id')
  }, function (data) {
    $('#livesearch').html(data);
    if (data) {
      $('#livesearch').slideDown(100);
      $('.topnav-wrapper').slideUp(100);
      $('.logo-holder').slideUp(100);
    } else {
      $('#livesearch').hide();
      $('.topnav-wrapper').slideDown(100);
      $('.logo-holder').slideDown(100);
    }
  });
}
$('.action_play').click(function () {
  $(this).animateCss('flash');
  var sound = new Audio($(this).data('sound'));
  sound.play();
});

function getAllComments(type, item_id, p1, p2, h, toggle, refresh_only, np, l) {
  var comment_holder = $('#comments-holder-' + type + '-' + item_id);
  var comments_limit = $('#comments_limit-' + type + '-' + item_id);
  var container_arrow = '.arr_' + type + '_' + item_id;
  if (comment_holder.is(':visible') === false || refresh_only == true) {
    comments_limit.addClass('ajax_loader_bg');
    $.post("/action/comments/_get_comments.php", {
      type: type,
      item_id: item_id,
      p1: p1,
      p2: p2,
      np: np,
      l: l,
      h: h
    }, function (data) {
      comment_holder.html(data);
      comments_limit.removeClass('ajax_loader_bg');
      if (toggle == true) {
        toggleWithArrows(comment_holder, container_arrow);
      }
    });
  } else {
    if (refresh_only == false) {
      toggleWithArrows(comment_holder, container_arrow);
    }
  }
}

function getReviewText(id, h) {
  $('.collapse-link-' + id).html('').addClass('ajax_loader_div_center');
  $.post("/action/perfume/get_reviews_text.php", {
    id: id,
    h: h
  }, function (data) {
    $('#r_text_' + id).html(data).slideDown('fast');
  });
}

function getOriginalReviewTexts(id, o, h, csrf_key) {
  if (o != '') {
    $('.link_l_t_' + id + ' span').append(' <i class="fa fa-spinner fa-spin lightgrey"></i>');
  } else {
    $('.link_l_o_' + id + ' span').append(' <i class="fa fa-spinner fa-spin lightgrey"></i>');
  }
  $.post("/action/_get_review_original.php", {
    id: id,
    o: o,
    h: h,
    csrf_key: csrf_key
  }, function (data) {
    var json = JSON.parse(data);
    $('.link_l_o_' + id + ' .fa-spin, .link_l_t_' + id + ' .fa-spin').remove();
    if (o != '') {
      $('.link_l_t_' + id).hide();
      $('.link_l_o_' + id).fadeIn('fast');
    } else {
      $('.link_l_o_' + id).hide();
      $('.link_l_t_' + id).fadeIn('fast');
    }
    $('#r_title_' + id).html(json.review_title);
    $('#r_text_' + id).html(json.review_text);
  });
}

function getOriginalCommentText(id, o, h, csrf_key) {
  $('.link_l_o_' + id).hide();
  $('.link_l_t_' + id).hide();
  $.post("/action/comments/_get_comment_translation.php", {
    id: id,
    o: o,
    h: h,
    csrf_key: csrf_key
  }, function (data) {
    var json = JSON.parse(data);
    $('.comment-text-' + id).html(json.text);
    if (o != '') {
      $('.link_l_o_' + id).show();
    } else {
      $('.link_l_t_' + id).show();
    }
  });
}

function ratingsComparer(index) {
  return function (a, b) {
    var valA = getRatingsCellValue(a, index),
      valB = getRatingsCellValue(b, index)
    return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
  }
}

function getRatingsCellValue(row, index) {
  return $(row).children('td').eq(index).data('v')
}

function ratingDetails(el, label_color, csrf_key) {
  $('.barfiller_element').addClass('greyscale opacity-50');
  el.removeClass('greyscale opacity-50');
  el.find('i').toggleClass('toggle-up toggle-down');
  var details_type = el.data('type');
  var p_id = el.data('p_id');
  var h = el.data('h');
  var block = el.data('block');
  var voting_distribution = el.data('voting_distribution');
  if ($('#rating_details').is(':visible') !== false && $('#rating_details').data('type') == details_type) {
    $('#rating_details').slideUp('fast').remove();
    $('.barfiller_element').removeClass('greyscale opacity-50');
  } else {
    if ($('#rating_details').is(':visible') !== false) {
      $('#rating_details').remove();
    }
    if ($(window).width() > 1065 && (block == 1 || block == 3) && $('.barfiller_element').length > 1) {
      block++;
    }
    if ($(window).width() <= 767) {
      $('html, body').animate({
        scrollTop: $(el).offset().top - 50
      }, 'fast');
    }
    $('*[data-block="' + block + '"]').after('<div id="rating_details" class="grey-box w-100 hide" data-type="' + details_type + '"><div class="ajax_loader_div_center"></div></div>');
    $('#rating_details').slideDown('fast');
    $.post("/action/_get_ratings_details.php", {
      type: details_type,
      p_id: p_id,
      dist: voting_distribution,
      csrf_key: csrf_key,
      h: h
    }, function (data) {
      ratingDetailsHTML(el, data, label_color);
    });
  }
}

function ratingDetailsHTML(el, data, label_color) {
  var html = '';
  var html_dist = '';
  var mod = '';
  var mod_tablerow = '';
  user_ratings = jQuery.parseJSON(data);
  $.each(user_ratings.list, function (i, item) {
    if (item.mod !== undefined) {
      mod = " <i class=\"fa fa-user-secret lightblue pointer\" onclick=\"modContent('" + item.mod + "','" + item.id + "');\"></i>";
      mod_tablerow = ' id="tablerow' + item.id + '"';
    }
    html += '<tr' + mod_tablerow + '><td class="vot bold text-right blue" data-v="' + item.r + '">' + item.r + '</td><td data-v="' + item.u + '"><a href="' + user_ratings.url_users + '/' + item.u + '"><i class="fa fa-user ' + item.c + '" aria-hidden="true"></i> ' + item.u + '</a></td><td class="text-xs lightblue2" data-v="' + item.sec + '">' + item.d + mod + '</td></tr>';
  });
  html = '<table class="voting_distribution_table w-100"><tr><th class="text-left pointer"><i class="dist-u-sort fa fa-chevron-circle-down lightgrey opacity-25"></i></th><th class="text-left pointer"><i class="dist-u-sort fa fa-chevron-circle-down lightgrey opacity-25"></i></th><th class="text-left pointer"><i class="dist-u-sort fa fa-chevron-circle-up lightgrey"></i></th></tr>' + html + '</table>';
  html_dist = '<div class="rating_details_close magic_modal_content_close_sm"></div><h4><span class="bold">' + user_ratings.title_dist + '</span> - ' + user_ratings.title + '</h4><div id="chartdiv" class="chart_placeholder_ratings"></div>';
  $('#rating_details').html(html_dist + html);
  $('.rating_details_close').click(function () {
    $(el).click();
  });
  $('th').click(function () {
    $('.dist-u-sort').removeClass('opacity-25').addClass('opacity-25');
    $(this).children('i').removeClass('opacity-25').toggleClass('fa-chevron-circle-up fa-chevron-circle-down');
    var table = $(this).parents('table').eq(0)
    var rows = table.find('tr:gt(0)').toArray().sort(ratingsComparer($(this).index()))
    this.asc = !this.asc
    if (!this.asc) {
      rows = rows.reverse()
    }
    for (var i = 0; i < rows.length; i++) {
      table.append(rows[i])
    }
  });
  require.config({
    baseUrl: "/js/plugins/",
    shim: {
      'amcharts4/core': {
        init: function () {
          return window.am4core;
        }
      },
      'amcharts4/charts': {
        deps: ['amcharts4/core'],
        exports: 'amcharts4/charts',
        init: function () {
          return window.am4charts;
        }
      },
      'amcharts4/themes/animated': {
        deps: ['amcharts4/core'],
        exports: 'amcharts4/themes/animated',
        init: function () {
          return window.am4themes_animated;
        }
      }
    }
  });
  require(['amcharts4/core', 'amcharts4/charts', 'amcharts4/themes/animated'], function (am4core, am4charts, am4themes_animated) {
    am4core.addLicense("CH59076979");
    am4core.useTheme(am4themes_animated);
    var chart = am4core.create("chartdiv", am4charts.XYChart);
    chart.data = user_ratings.dist;
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "r";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 20;
    categoryAxis.renderer.labels.template.fill = am4core.color("#" + label_color);
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.renderer.labels.template.fill = am4core.color("#" + label_color);
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.columns.template.fill = am4core.color(user_ratings.color);
    series.dataFields.valueY = "v";
    series.dataFields.categoryX = "r";
    series.columns.template.tooltipText = "{valueY}";
    series.columns.template.fillOpacity = 1;
    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 0;
  });
}
var prev_note_popup = 0;
var prev_note_popup_target = '';

function notesPopup(p_id, nt, note_id, locked, csrf_key) {
  var notes_info_popup = $('#notes_info_popup');
  var notes_info_popup_visible = $('#notes_info_popup').is(':visible');
  var target_element = $('.nb_' + nt);
  var different_note_type = (prev_note_popup_target != '' && prev_note_popup_target != target_element);
  if (prev_note_popup == note_id && notes_info_popup_visible !== false) {
    notes_info_popup.slideUp('fast').remove();
  } else {
    if (prev_note_popup != note_id || different_note_type || notes_info_popup_visible === false) {
      prev_note_popup = note_id;
      prev_note_popup_target = target_element;
      if (different_note_type) {
        notes_info_popup.remove();
        notes_info_popup_visible = false;
      }
      if (notes_info_popup_visible !== false) {
        notes_info_popup.html('<div class="ajax_loader_div_center"></div>');
      } else {
        target_element.after('<div class="n_popup"><div id="notes_info_popup" class="grey-box mt-1 mb-3"><div class="mt-4 mb-4"><div class="ajax_loader_div_center"></div></div></div>');
      }
      if (target_element.length > 0) {
        $('html, body').animate({
          scrollTop: target_element.offset().top - 100
        }, 0);
      }
      setTimeout(function () {
        $.post("/action/notes_popup.php", {
          p_id: p_id,
          note_id: note_id,
          locked: locked,
          csrf_key: csrf_key
        }, function (data) {
          var notes_info_popup = $('#notes_info_popup');
          notes_info_popup.html(data).slideDown();
        });
      }, 250);
    }
  }
}

function getConcentrationsPopup(p_id, p_ce) {
  $('.p_con i').toggleClass('toggle-up toggle-down');
  if ($('#con_info_popup').is(':visible') !== false) {
    $('#con_info_popup').slideUp('fast').remove();
  } else {
    $('.p_name_h1').after('<div id="con_info_popup" class="grey-box relative mb-1 hide"><div class="ajax_loader_div_center"></div></div>');
  }
  $('#con_info_popup').slideDown('fast');
  $.get("/action/_get_concentrations.php", {
    p_id: p_id,
    p_ce: p_ce
  }, function (data) {
    $('#con_info_popup').html(data);
    myLazyLoad.update();
  });
}

function orderPerfumeDetailsBoxes(order) {
  $('.pd-options-box').hide();
  if (!order || order.length < 1) {
    return false
  }
  for (var i = 0; i < order.length; i++) {
    $('#pd_order').children('div[data-index="' + order[i] + '"]').appendTo('#pd_order');
  }
  $('.tabsPD>div.current').removeClass('current');
  $('html, body').animate({
    scrollTop: $('body').offset().top
  }, 'fast');
}

function hidePDOptions() {
  $('.pd-options-box').hide();
  if (window.screen.width < 767) {
    var elementTop = $('#pd_options').offset().top;
    $('html, body').animate({
      scrollTop: elementTop
    }, '');
  }
}

function scrollToPDOptions() {
  var offset;
  if (window.screen.width < 767) {
    var element = $('.pd_options.active');
    console.log(element);
    offset = 12;
  } else {
    var element = $('.pd-nav');
    offset = 72;
  }
  if (element.length) {
    var elementTop = element.offset().top;
    $('html, body').animate({
      scrollTop: elementTop - offset
    }, 100);
  } else {
    console.log("Element with class '.pd_options .active' not found.")
  }
}

function toggleWithArrows(el, el_arr) {
  if ($(el).css('display') == 'none') {
    $(el_arr).toggleClass('toggle-up');
    $(el).slideDown('fast');
  } else {
    $(el_arr).toggleClass('toggle-up');
    $(el).slideUp('fast');
  }
}

function liveSearchKeyNav(el, input) {
  var currCell = $(el + ' tr:first');
  if (window.screen.width > 767) {
    currCell.children('td').addClass('active');
    $(input).off().keydown(function (e) {
      var c = "";
      if (e.which == 38) {
        c = currCell.closest('tr').prev('tr');
      } else if (e.which == 40) {
        c = currCell.closest('tr').next('tr');
      } else if (e.which == 13) {
        c = currCell.closest('tr');
        c.children('td').children('div').click();
        e.preventDefault();
      }
      if (c.length > 0) {
        currCell.children('td').removeClass('active');
        currCell = c;
        currCell.children('td').addClass('active');
      }
    });
  }
}
