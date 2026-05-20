(function() {
    const correctPassword = "your_password";

    function showModal() {
        $('#lockModal').modal('show');
    }

    function hideModal() {
        $('#lockModal').modal('hide');
    }

    $(document).ready(function() {
        var isLoggedIn = localStorage.getItem('isLoggedIn');
        if (isLoggedIn) {
            hideModal();
        } else {
            showModal();
        }

        var isDisabledLock = localStorage.getItem('isDisabledLock');
        if (isDisabledLock === "true") {
            $('#disableLock').prop('checked', true);
            hideModal();
        } else {
            $('#disableLock').prop('checked', false);
            showModal();
        }
    });

    $('#checkPassword').click(function() {
        var password = $('#passwordInput').val();
        if (password === correctPassword) {
            localStorage.setItem('isLoggedIn', true);
            hideModal();
            toastr.success('Thành công - Mở khóa màn hình');
        } else {
            toastr.error('Thất bại - Hãy nhập lại mật khẩu để mở khóa màn hình');
        }
    });

    // function checkPassword() {
    //     const passwordInput = document.getElementById("passwordInput").value;

    //     // Gửi Ajax request
    //     $.ajax({
    //         url: "",
    //         method: "POST",
    //         data: {
    //             password: passwordInput,
    //             _token: "{{ csrf_token() }}",
    //         },
    //         success: function (response) {
    //             if (response.success) {
    //                 // Mật khẩu đúng, ẩn modal
    //                 hideLockScreen();
    //             } else {
    //                 // Mật khẩu sai, hiển thị thông báo lỗi
    //                 errorMessage.textContent =
    //                     "Incorrect password. Please try again.";
    //             }
    //         },
    //     });
    // }

    $('#saveSettings').click(function() {
        localStorage.setItem('modalSeconds', parseInt($('#minutesInput').val()));
        toastr.success('Thành công - Cập nhật lại thời gian hiển thị màn hình khóa');
    });

    $('#disableLock').click(function() {
        var isChecked = $(this).is(':checked');
        if (isChecked) {
            localStorage.setItem('isDisabledLock', true);
        } else {
            localStorage.setItem('isDisabledLock', false);
        }
    });

    var timeoutSeconds = parseInt(localStorage.getItem('modalSeconds')) || 60;
    setTimeout(function() {
        var isDisabledLock = localStorage.getItem('isDisabledLock');
        if (isDisabledLock !== "true") {
            localStorage.removeItem('isLoggedIn');
            showModal();
        }
    }, timeoutSeconds * 1000);
})();
