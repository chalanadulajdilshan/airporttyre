jQuery(document).ready(function () {

    // Create Income
    $("#create").click(function (event) {
        event.preventDefault();

        if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter income name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#amount').val() || $('#amount').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter amount",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#date').val() || $('#date').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select date",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            $('.someBlock').preloader();

            var formData = new FormData($("#form-data")[0]);
            formData.append('create', true);

            $.ajax({
                url: "ajax/php/income.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (result) {
                    $('.someBlock').preloader('remove');

                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Income added successfully!",
                            type: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        swal({
                            title: "Error!",
                            text: "Something went wrong.",
                            type: 'error',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
        return false;
    });

    // Update Income
    $("#update").click(function (event) {
        event.preventDefault();

        if (!$('#name').val() || !$('#amount').val() || !$('#date').val()) {
            swal({
                title: "Error!",
                text: "Please fill all required fields.",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            $('.someBlock').preloader();

            var formData = new FormData($("#form-data")[0]);
            formData.append('update', true);

            $.ajax({
                url: "ajax/php/income.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (result) {
                    $('.someBlock').preloader('remove');

                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Income updated successfully!",
                            type: 'success',
                            timer: 2500,
                            showConfirmButton: false
                        });
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        swal({
                            title: "Error!",
                            text: "Something went wrong.",
                            type: 'error',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
        return false;
    });

    // Reset input fields
    $("#new").click(function (e) {
        e.preventDefault();
        $('#form-data')[0].reset();
        $("#create").show();
        $("#update").hide();
    });

    // Populate form from modal click
    $(document).on('click', '.select-income', function () {
        $('#income_id').val($(this).data('id'));
        $('#name').val($(this).data('name'));
        $('#amount').val($(this).data('amount'));
        $('#date').val($(this).data('date'));

        $("#create").hide();
        $("#update").show();
        $('#income_master').modal('hide');
    });

    // Delete Income
    $(document).on('click', '.delete-income', function (e) {
        e.preventDefault();

        var incomeId = $('#income_id').val();
        var incomeName = $('#name').val();

        if (!incomeId || incomeId === "") {
            swal({
                title: "Error!",
                text: "Please select an income first.",
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        swal({
            title: "Are you sure?",
            text: "Do you want to delete income '" + incomeName + "'?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $('.someBlock').preloader();

                $.ajax({
                    url: 'ajax/php/income.php',
                    type: 'POST',
                    data: {
                        id: incomeId,
                        delete: true
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        $('.someBlock').preloader('remove');

                        if (response.status === 'success') {
                            swal({
                                title: "Deleted!",
                                text: "Income has been deleted.",
                                type: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            setTimeout(() => window.location.reload(), 2000);
                        } else {
                            swal({
                                title: "Error!",
                                text: "Something went wrong.",
                                type: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            }
        });
    });
});
