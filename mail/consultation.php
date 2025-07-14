<?php
// إعدادات البريد الإلكتروني
$to = "aimenelwahab@gmail.com";
$subject = "طلب استشارة جديد - بابل";

// التحقق من إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // جمع البيانات من النموذج
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $consultation_type = isset($_POST['consultation_type']) ? $_POST['consultation_type'] : '';
    $project_size = isset($_POST['project_size']) ? $_POST['project_size'] : '';
    $budget = isset($_POST['budget']) ? $_POST['budget'] : '';
    $timeline = isset($_POST['timeline']) ? $_POST['timeline'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $preferred_contact = isset($_POST['preferred_contact']) ? $_POST['preferred_contact'] : '';
    
    // التحقق من البيانات المطلوبة
    if (empty($name) || empty($phone) || empty($email) || empty($consultation_type)) {
        echo json_encode(['status' => 'error', 'message' => 'يرجى ملء جميع الحقول المطلوبة']);
        exit;
    }
    
    // بناء محتوى الرسالة
    $message = "
    <html>
    <head>
        <title>طلب استشارة جديد</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #E81C2E; color: white; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #E81C2E; }
            .value { margin-top: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>طلب استشارة جديد</h1>
                <p>تم استلام طلب استشارة جديد من موقع بابل</p>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='label'>الاسم الكامل:</div>
                    <div class='value'>$name</div>
                </div>
                
                <div class='field'>
                    <div class='label'>رقم الهاتف:</div>
                    <div class='value'>$phone</div>
                </div>
                
                <div class='field'>
                    <div class='label'>البريد الإلكتروني:</div>
                    <div class='value'>$email</div>
                </div>
                
                <div class='field'>
                    <div class='label'>المدينة:</div>
                    <div class='value'>$city</div>
                </div>
                
                <div class='field'>
                    <div class='label'>نوع الاستشارة:</div>
                    <div class='value'>$consultation_type</div>
                </div>
                
                <div class='field'>
                    <div class='label'>حجم المشروع:</div>
                    <div class='value'>$project_size</div>
                </div>
                
                <div class='field'>
                    <div class='label'>الميزانية المتوقعة:</div>
                    <div class='value'>$budget</div>
                </div>
                
                <div class='field'>
                    <div class='label'>الجدول الزمني المطلوب:</div>
                    <div class='value'>$timeline</div>
                </div>
                
                <div class='field'>
                    <div class='label'>طريقة التواصل المفضلة:</div>
                    <div class='value'>$preferred_contact</div>
                </div>
                
                <div class='field'>
                    <div class='label'>وصف المشروع والتفاصيل:</div>
                    <div class='value'>$description</div>
                </div>
                
                <div class='field'>
                    <div class='label'>تاريخ الطلب:</div>
                    <div class='value'>" . date('Y-m-d H:i:s') . "</div>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // إعدادات البريد الإلكتروني
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: $email" . "\r\n";
    $headers .= "Reply-To: $email" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // إرسال البريد الإلكتروني
    if (mail($to, $subject, $message, $headers)) {
        // إرسال رسالة تأكيد للعميل
        $client_subject = "تأكيد طلب الاستشارة - بابل";
        $client_message = "
        <html>
        <head>
            <title>تأكيد طلب الاستشارة</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #E81C2E; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>شكراً لك!</h1>
                </div>
                <div class='content'>
                    <p>عزيزي/عزيزتي <strong>$name</strong>،</p>
                    <p>شكراً لك على طلب الاستشارة. لقد تم استلام طلبك بنجاح وسيقوم فريقنا بالتواصل معك في أقرب وقت ممكن.</p>
                    <p>تفاصيل طلبك:</p>
                    <ul>
                        <li><strong>نوع الاستشارة:</strong> $consultation_type</li>
                        <li><strong>تاريخ الطلب:</strong> " . date('Y-m-d H:i:s') . "</li>
                    </ul>
                    <p>سنقوم بالتواصل معك عبر $preferred_contact في أقرب وقت ممكن.</p>
                    <p>مع تحيات،<br>فريق بابل</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $client_headers = "MIME-Version: 1.0" . "\r\n";
        $client_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $client_headers .= "From: noreply@babel-construction.com" . "\r\n";
        
        mail($email, $client_subject, $client_message, $client_headers);
        
        echo json_encode(['status' => 'success', 'message' => 'تم إرسال طلب الاستشارة بنجاح! سنقوم بالتواصل معك قريباً.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.']);
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'طريقة طلب غير صحيحة']);
}
?> 