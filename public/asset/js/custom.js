$(document).ready(function() {
    var table = $('#dataTable').DataTable({
        responsive: true
    });
    var table = $('#show-users-table').DataTable({
        responsive: true,
        "paging":   false,
        "bInfo" : false,
        dom: 'Bfrtip',
        buttons: [
      		{
            	extend: 'excelHtml5',
                text: 'Download',
             	className: "smallbluebtn user-report",
                filename:'User-List',
                exportOptions: {
              		columns: ':visible'
                }
      		},
        ],
    });
    $('.selectpicker').selectpicker();
});

$(function() {
    // setTimeout() function will be fired after page is loaded
    // it will wait for 4 sec. and then will fire
    // hide alert msg after 4 sec on page
    setTimeout(function() {
        $(".alert").fadeOut('slow');
    }, 4000);
});

$('[role="my-select"]').on('changed.bs.select', function(e, clickedIndex) {
    if (clickedIndex !== 0) {
        return;
    }
    if (this.value === 'all') {
        return $(this).selectpicker('selectAll');
    }
    $(this).selectpicker('deselectAll');
});

/*--------------------------Subject Page JavaScript--------------------------------*/
$("body").on('click', ".edit_subject_btn", function() {
    $("#edit-subject-form")[0].reset();
    var subject_name = $(this).data('subject_name');
    var subject_id = $(this).data('subject_id');
    var logo_image = $(this).data('logo_image');
    $("#update_subject_name").val(subject_name);
    $("#subject_id").val(subject_id);
    $("#old_subject_logo").val(logo_image);
});

$('#subject_logo, #update_subject_logo').on('change', function(e) {
    var id = $(this).attr('id');
    class_name = 'add';
    if (id == "update_subject_logo") {
        class_name = 'edit';
    }
    var fileName = e.target.files[0].name;
    var fileSize = e.target.files[0].size;
    var fileSizeInMB = (fileSize / 1048576);
    $('.show-file-name-campaign.' + class_name).html(fileName);
    $('.show-file-size-campaign.' + class_name).html(fileSizeInMB.toFixed(2) + ' MB');
    $('.preview-container.' + class_name).css('display', 'block');
});

$(".remove-upload-file").on('click', function() {
    if (class_name == 'add') {
        $('#subject_logo').val('');
    } else {
        $('#update_subject_logo').val('');
    }
    $(".preview-container." + class_name).css('display', 'none');
});

$('input[type=radio][name=update_logo]').change(function() {
    if (this.value == 'yes') {
        $("#edit-subject-logo-div").show();
        $("#edit-subject-logo-div :input").removeAttr('disabled');
    } else if (this.value == 'no') {
        $("#edit-subject-logo-div").hide();
        $("#edit-subject-logo-div :input").attr('disabled', true);
    }
});
$("body").on('click', ".delete_subject_btn", function() {
    $("#delete-subject-form")[0].reset();
    var subject_id = $(this).data('subject_id');
    $("#delete_subject_id").val(subject_id);
});

$("#add-subject-form").submit(function() {
    $('.add-submit-btn').prop('disabled', true);
    $('.normal-loader').show();
});

$("#edit-subject-form").submit(function() {
    $('.edit-submit-btn').prop('disabled', true);
    $('.normal-loader').show();
});

$("body").on('click', ".delete-submit-btn", function() {
    $('.delete-submit-btn').prop('disabled', true);
    $('.normal-loader').show();
});
/*--------------------------End of Subject Page JavaScript--------------------------------*/

//

/*--------------------------Class Page JavaScript--------------------------------*/
$("body").on('click', ".edit_class_btn", function() {
    $("#edit-class-form")[0].reset();
    var class_name = $(this).data('class_name');
    var subjects = $(this).data('subjects');
    var class_id = $(this).data('class_id');
    var class_fee = $(this).data('class_fee');
    $("#update_class_name").val(class_name);
    $("#update_class_fee").val(class_fee);
    $("#class_id").val(class_id);
    $.each(subjects, function(key, value) {
        $('select[name="update_class_subjects[]"]').find('option[value="' + value + '"]').attr("selected", true);
    });
    $('.selectpicker').selectpicker('refresh');
});

$("body").on('click', ".delete_class_btn", function() {
    $("#delete-class-form")[0].reset();
    var class_id = $(this).data('class_id');
    $("#delete_class_id").val(class_id);
});

$("#add-class-form").submit(function() {
    $('.add-submit-btn').prop('disabled', true);
    $('.normal-loader').show();
});

$("#edit-class-form").submit(function() {
    $('.edit-submit-btn').prop('disabled', true);
    $('.normal-loader').show();
});

$("body").on('click', ".delete-submit-btn", function() {
    $('.delete-submit-btn').prop('disabled', true);
    $('.normal-loader').show();
});
/*--------------------------End of Class Page JavaScript--------------------------------*/

//

/*--------------------------Video Page JavaScript--------------------------------*/
$('#thumbnail').on('change', function(e) {
    var fileName = e.target.files[0].name;
    var fileSize = e.target.files[0].size;
    var fileSizeInMB = (fileSize / 1048576);
    $('.show-file-name-campaign.thumbnail').html(fileName);
    $('.show-file-size-campaign.thumbnail').html(fileSizeInMB.toFixed(2) + ' MB');
    $('.preview-container.thumbnail').css('display', 'block');
});

$(".remove-thumbnail-file").on('click', function() {
    $('#thumbnail').val('');
    $(".preview-container.thumbnail").css('display', 'none');
});

$('#video_file').on('change', function(e) {
    var fileName = e.target.files[0].name;
    var fileSize = e.target.files[0].size;
    var fileSizeInMB = (fileSize / 1048576);
    $('.show-file-name-campaign.video_file').html(fileName);
    $('.show-file-size-campaign.video_file').html(fileSizeInMB.toFixed(2) + ' MB');
    $('.preview-container.video_file').css('display', 'block');
});

$(".remove-video-file").on('click', function() {
    $('#video_file').val('');
    $(".preview-container.video_file").css('display', 'none');
});

$('#quiz_file').on('change', function(e) {
    var fileName = e.target.files[0].name;
    var fileSize = e.target.files[0].size;
    var fileSizeInMB = (fileSize / 1048576);
    $('.show-file-name-campaign.quiz_file').html(fileName);
    $('.show-file-size-campaign.quiz_file').html(fileSizeInMB.toFixed(2) + ' MB');
    $('.preview-container.quiz_file').css('display', 'block');
});

$(".remove-quiz-file").on('click', function() {
    $('#quiz_file').val('');
    $(".preview-container.quiz_file").css('display', 'none');
});

// get subjects class wise
$('#class_id').on('change', function() {
    var class_id = this.value
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type: 'POST',
        url: '/get-class-subjects',
        data: {
            class_id: class_id
        },
        dataType: "json",
        beforeSend: function() {
            $('.normal-loader').show();
        },
        success: function(data) {
            $('.normal-loader').hide();
            if(data.status == 'success') {
                $('#subject_id').empty();
                $("#subject_id").append('<option value="" selected disabled hidden>Choose here</option>');
                $.each(data.data, function(key, value) {
                    $("#subject_id").append(new Option(value.class_wise_subjects.subject_name, value.subject_id));
                });
                $('#subject_id').removeAttr("disabled");
                $('.selectpicker').selectpicker('refresh');
            } else if(data.status == 'error') {
                $('#subject_id').attr('disabled', true);
            }
        }
    });
});

// upload video file form
$('#add-video-form').ajaxForm({
    beforeSend: function() {
        $('.upload-loader').show();
    },
    uploadProgress: function(event, position, total, percentComplete) {
        $('.progress-bar').text(percentComplete + '%');
        $('.progress-bar').css('width', percentComplete + '%');
    },
    success: function(data) {
        if (data.status == 'error' && data.msg_code == '1') {
            $('.upload-loader').hide();
            $(".form-text").show();
            $(".invalid-feedback").text('').hide();
            $.each(data.msg, function(key, val) {
                $("#" + key + "_error").text('');
                $(".form-text." + key).hide();
                $("#" + key + "_error").text(val[0]).show();
            });
        } else if (data.status == 'success') {
            $('.progress-bar').text('Uploaded');
            $('.progress-bar').css('width', '100%');
            swal({
                title: "Video Uploaded Successfully",
                text: "Upload Next Video?",
                icon: "success",
                buttons: ["Yes", "No"],
                dangerMode: false,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = base_url + '/view-videos';
                } else {
                    window.location.href = base_url + '/add-video';
                }
            });
        }
    }
});

// search videos ajax
$("#search_videos").keyup(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type: 'POST',
        url: '/get-filters-videos',
        data: {
            search_videos: $("#search_videos").val()
        },
        beforeSend: function() {
            $('.normal-loader').show();
        },
        success: function(data) {
            $('.normal-loader').hide();
            if(data.status == 'success') {
                $(".view-video-table").html('');
                $(".view-video-table").html(data.data);
            } else if(data.status == 'error') {
                $(".view-video-table").html('');
                $(".view-video-table").html(data.data);
            }
        }
    });
});
/*--------------------------End of Video Page JavaScript--------------------------------*/

//

/*--------------------------Payment Page JavaScript--------------------------------*/

// filter payments using status
$('#payment_status_option').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type: 'POST',
        url: '/get-payments-data',
        data: {
            payment_status: valueSelected
        },
        beforeSend: function() {
            $('.normal-loader').show();
        },
        success: function(data) {
            $('.normal-loader').hide();
            if(data.status == 'success') {
                $("#view-payments-table").html('');
                $("#view-payments-table").html(data.data);
            } else if(data.status == 'error') {
                $("#view-payments-table").html('');
                $("#view-payments-table").html(data.data);
            }
            var table = $('#show-users-table').DataTable({
                responsive: true,
                "paging":   false,
                "bInfo" : false,
                dom: 'Bfrtip',
                buttons: [
              		{
                    	extend: 'excelHtml5',
                        text: 'Download',
                     	className: "smallbluebtn user-report",
                        filename:'User-List',
                        exportOptions: {
                      		columns: ':visible'
                        }
              		},
                ],
            });
        }
    });
});

/*--------------------------End of Payment Page JavaScript--------------------------------*/
