<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>您的发票</title>
</head>

<body>
    <h2>感谢您的订阅！</h2>

    <p>尊敬的 {{ $user->name }},</p>

    <p>感谢您订阅我们的 {{ $plan->name }} 计划。请查看附件中的发票 #{{ $invoiceNumber }}。</p>

    <p>如果您对订阅或发票有任何疑问，请随时与我们联系。</p>

    <p>此致敬礼,<br>{{ config('app.name') }} 团队</p>
</body>

</html>