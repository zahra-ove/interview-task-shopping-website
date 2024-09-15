## Installation guide

the task is running based on docker.
all experiments are done on Windows machine with `docker desktop`.

1. clone the project:\
   `git clone https://github.com/zahra-ove/interview-task-shopping-website.git`

2. go to docker directory via this command:\
   `cd interview-task-shopping-website/docker`

3. run docker-compose:\
   `docker-compose up -d --build`

this command may take time because of "composer install" command in it.

4. all `postman` routes are available in `interview-task-shopping-website/postman-result` directory.


*note:\
in this project we have three containers:
- backend container which is laravel-app
- mongoDB official image
- redis official image


<br/>
<br/>
<hr/>
<div dir="rtl">
توضیحات مربوط به جزییات تسک:
<br><br>

#<mark>Route</mark>

سه گروه کلی روت در پروژه تعریف شده:
- Authentication
- Product
- Order

روت بخش Product به دو دسته پنل ادمین و روت های مربوط به فروشگاه تقسیم شد.

روت Order، شامل عملیات :
- افزودن کالا به سبد خرید
- کاهش/افزایش تعداد کالای موجود در سبد خرید
- حذف کلی یک کالا از سبد خرید

<br><br>

#<mark>Cart</mark>

محتوای سبد خرید تا قبل از پرداخت نهایی توسط کاربر در Cache با درایور  Redis نگهداری میشود.
قبل از هدایت کاربر به صفحه پرداخت، موجودی انبار چک میشود. در صورتی که موجودی هر یک از اقلام سبد خرید صفر باشد، روال قطع شده و پیام عدم موجودی نمایش داده میشود.
در صورتی که موجودی انبار از موجودی خواسته شده توسط کاربر کمتر باشد، موجودی انبار برای کاربر ثبت میشود. به عبارتی اگر کاربر 3 خودکار خریده باشد اما در انبار 2 خودکار موجود باشد، همان 2 خودکار در سبد خرید کاربر در نظر گرفته میشود و تمام محاسبات مالی با توجه به این تعداد جدید محاسبه میشود.

<br><br>

#<mark>Authentication</mark>

برای بخش  Authentication  به روش JWT token از کتابخانه  "php-open-source-saver/jwt-auth" استفاده شده است.
توکن ها از روش JWS ساخته میشوند بنابراین نیاز به یک  secret key میباشد که با دستور   php arisan jwt:secret  این کد به فایل   env. پروژه لاراول اضافه میشود و تمام JWT Token ها با کمک این  secret key، ساین میشوند.



*** معمولا access token  با عمر کوتاه در حدود 5 دقیقه تولید میشود اما به جهت اینکه در فرآیند تست، مرتب مجبور به لاگین نباشیم، مدت اعتبار این توکن در فایل  config/jwt.php برابر  یک ساعت تنظیم شده است.

<br><br>

#<mark>Postman</mark>

برای تست روتها،
روت  login فراخونی شده و از پاسخ دریافت شده، مقدار کلید  token را در بخش environment برنامه postman ( بخش global) کپی کنیم. نام این متغیر global برابر  token باشد.
تمامی روت هایی که با میدلور  auth:api محدود شده اند، در تب  Authorization هر ریکوئست (در postman)، لطفا مقدار  Auth Type برابر   Bearer token انتخاب و مقدار value آن برابر    {{token}} قرار داده شود.



</div>
