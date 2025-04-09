<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>发票 #{{ $invoiceNumber }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            margin-bottom: 30px;
        }
        .company-info {
            float: left;
            width: 60%;
        }
        .invoice-details {
            float: right;
            width: 40%;
            text-align: right;
        }
        .clear {
            clear: both;
        }
        .customer-info {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f5f5f5;
        }
        .totals {
            float: right;
            width: 40%;
        }
        .footer {
            margin-top: 50px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <h2>{{ config('app.name') }}</h2>
            <p>Heveweg 8<br>59821 Arnsberg<br>Germany</p>
        </div>
        <div class="invoice-details">
            <h1>发票</h1>
            <p>
                <strong>发票编号:</strong> {{ $invoiceNumber }}<br>
                <strong>日期:</strong> {{ $invoiceDate }}
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="customer-info">
        <p>
            <strong>{{ $company->company_name }}</strong><br>
            {{ $company->address }}<br>
            @if($company->phone)电话: {{ $company->phone }}<br>@endif
            @if($company->website)网站: {{ $company->website }}@endif
        </p>
        
        <p>尊敬的客户,</p>
        <p>非常感谢您对我们公司的信任。以下是我们为您提供服务的发票:</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>序号</th>
                <th>描述</th>
                <th>数量</th>
                <th>单位</th>
                <th>单价</th>
                <th>总价</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1.</td>
                <td>{{ $plan->name }} 订阅</td>
                <td>1</td>
                <td>{{ $plan->billing_period === 'month' ? '月' : '年' }}</td>
                <td>{{ number_format($plan->price, 2, ',', '.') }}</td>
                <td>{{ number_format($plan->price, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>净额</td>
                <td>{{ number_format($plan->price, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>总计</strong></td>
                <td><strong>{{ number_format($plan->price, 2, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>

    <div class="footer">
        <p>此致敬礼,</p>
        <p>{{ config('app.name') }} 团队</p>
        
        <div style="margin-top: 30px;">
            <div style="float: left; width: 50%;">
                <p>
                    <strong>{{ config('app.name') }}</strong><br>
                    Heveweg 8<br>
                    59821 Arnsberg<br>
                    Germany
                </p>
            </div>
            <div style="float: right; width: 50%;">
                <p>
                    <strong>Revolut</strong><br>
                    DE21 1001 0178 2255 0532 77<br>
                    DE36 7969 913
                </p>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html> 