$(document).ready(function() {
    // معالجة إرسال النموذج
    $('#consultationForm').on('submit', function(e) {
        e.preventDefault();
        
        // جمع البيانات من النموذج
        var formData = $(this).serialize();
        
        // إظهار رسالة التحميل
        $('.btn-submit').html('<i class="fa fa-spinner fa-spin"></i> جاري الإرسال...');
        $('.btn-submit').prop('disabled', true);
        
        // إرسال البيانات
        $.ajax({
            type: 'POST',
            url: 'mail/consultation.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // إظهار رسالة النجاح
                    showAlert('success', response.message);
                    // إعادة تعيين النموذج
                    $('#consultationForm')[0].reset();
                } else {
                    // إظهار رسالة الخطأ
                    showAlert('error', response.message);
                }
            },
            error: function() {
                // إظهار رسالة خطأ في الاتصال
                showAlert('error', 'حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.');
            },
            complete: function() {
                // إعادة تعيين الزر
                $('.btn-submit').html('إرسال طلب الاستشارة');
                $('.btn-submit').prop('disabled', false);
            }
        });
    });
    
    // دالة إظهار التنبيهات
    function showAlert(type, message) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                        message +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                        '</div>';
        
        // إزالة أي تنبيهات سابقة
        $('.alert').remove();
        
        // إضافة التنبيه الجديد
        $('.form-container').prepend(alertHtml);
        
        // إخفاء التنبيه تلقائياً بعد 5 ثوان
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
    
    // التحقق من صحة البريد الإلكتروني
    $('#email').on('blur', function() {
        var email = $(this).val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">يرجى إدخال بريد إلكتروني صحيح</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
    
    // التحقق من صحة رقم الهاتف
    $('#phone').on('blur', function() {
        var phone = $(this).val();
        var phoneRegex = /^[\+]?[0-9\s\-\(\)]{8,}$/;
        
        if (phone && !phoneRegex.test(phone)) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">يرجى إدخال رقم هاتف صحيح</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
    
    // التحقق من الحقول المطلوبة
    $('input[required], select[required], textarea[required]').on('blur', function() {
        if (!$(this).val()) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">هذا الحقل مطلوب</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
}); 