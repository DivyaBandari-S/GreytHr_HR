
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            line-height: 1.6;
            color: #333;
        }

        .content {
            margin: 20px;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.8em;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="content">
      
            <p>Hi <strong>{{ $formattedAssignee }}</strong>,</p>
            <p>The following task has been closed:</p>
   
        
        <p>Below are the task details:</p>
        
        <ul>
            <li><strong>Task Name: </strong> {{ strtoupper($taskName) }}</li>
            <li><strong>Description: </strong> {{ ucwords(strtolower($description)) }}</li>
            <li><strong>Due Date: </strong>
                @if($dueDate && \Carbon\Carbon::createFromFormat('Y-m-d', $dueDate))
                    {{ \Carbon\Carbon::parse($dueDate)->format('M d, Y') }}
                @else
                    N/A
                @endif 
            </li>
            <li><strong>Priority: </strong> {{ $priority }}</li>
            <li><strong>Assigned By: </strong> {{ ucwords(strtolower($assignedBy)) }}</li>
        </ul>

     
            <p><a href="https://s6.payg-india.com/tasks">Click here</a> to view the Task.</p>
           
       
    </div>

    <div class="footer">
        <p>Note: This is an auto-generated email. Please do not reply.</p>
        <p>PS: "This e-mail is generated from info@s6.payg-india.com"</p>
    </div>
</body>

</html>

