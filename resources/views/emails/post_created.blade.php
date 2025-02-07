<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post Created</title>
    <style>
        .headings{
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }
        .value{
            font-size: 14px;
            color: #000;
            font-weight: normal;
        }
        .reciverName{
            font-size: 14px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <p>Hi, <span class="reciverName"> {{ $managerName }}</span> </p>

    <p>A new post has been created by {{ $employeeDetails->first_name }} {{ $employeeDetails->last_name }}</p>

    <p class="headings"><b>Category: </b><span class="value">{{ $post->category }}</span></p>
    <p class="headings"><b>Description:</b> <span class="value">  {!! $post->description !!}</span> </p>


    <p>Thank you</p>

    <p>Note: This is an auto-generated mail. Please do not reply.</p>
    <p>PS: "This e-mail is generated from info@s6.payg-india.com"</p>
</body>
</html>
