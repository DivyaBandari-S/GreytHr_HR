<div class="letter-preview-page">
    <div class="header" style="text-align: center;">
        <p>Xsilica Software Solutions Pvt. Ltd.</p>
        <p>Unit No - 4, Kapil Kavuri Hub, 3rd floor, Nanakramguda, 
            Serilingampally, Ranga Reddy, Telangana-500032.</p>
    </div>

    <p>{{ now()->format('d M Y') }}</p>

    <p>To,<br>
        {{ $employee['name'] }}<br>  <!-- Accessing name from decoded array -->
        Employee ID: {{ $employee['id'] }}<br>  <!-- Accessing id -->
        {{ $employee['address'] }}
    </p>

    @if ($previewLetter['template_name'] == 'Appointment Order')
        <p class="text-align-center" style="font-size: 16px;"> <strong>Sub: Appointment Order</strong></p>
        <p><strong>Dear</strong> {{ $employee['name'] }},</p>
        <p>We are pleased to offer you the position of <strong>Software Engineer I</strong> at Xsilica Software Solutions Pvt. Ltd., as per the discussion we had with you. Below are the details of your appointment:</p>
        <p><strong>1. Start Date:</strong> 02 Jan 2023 (Your appointment will be considered withdrawn if you do not report to our office on this date.)</p>
        <p><strong>2. Compensation:</strong> Your Annual Gross Compensation is Rs. <strong>{{ number_format($previewLetter['ctc']) }}/-</strong> (subject to statutory deductions).</p>
        <p><strong>3. Probation Period:</strong> You will be under probation for six calendar months from your date of joining.</p>
        <p><strong>4. Confirmation of Employment:</strong> Upon successful completion of probation, you will be confirmed in employment.</p>
        <p><strong>5. Performance Reviews:</strong> You will undergo annual performance reviews and appraisals.</p>
        <p><strong>6. Absence from Duty:</strong> Unauthorized absence for 8 consecutive days will lead to termination of service.</p>
        <p><strong>7. Leave Policy:</strong> You are entitled to leave as per law and company policy, including one sick leave per month.</p>
        <p><strong>8. Confidentiality:</strong> Any products or materials developed during your employment will remain the property of Xsilica.</p>
        <p><strong>9. Termination of Employment:</strong> Voluntary resignation requires a 60-day notice period. Immediate termination can occur for consistent underperformance or providing incorrect information.</p>
        <p><strong>We are excited to have you as a part of our team and look forward to your contribution!</strong></p>
        <p>Thank you.</p>

    @elseif ($previewLetter['template_name'] == 'Confirmation Letter')
        <p class="text-align-center" style="font-size: 16px;"> <strong>Sub: Confirmation Letter</strong></p>
        <p><strong>Dear</strong> {{ $employee['name'] }},</p>
        <p>Further to your appointment/joining dated <strong>{{ $previewLetter['joining_date'] }}</strong>, your employment with us is confirmed with effect from <strong>{{ now()->format('d M Y') }}</strong>.</p>
        <p>All the terms mentioned in the Offer/Appointment letter will remain unaltered.</p>
        <p>We thank you for your contribution so far and hope that you will continue to perform equally well in the future.</p>
        <p><strong>We wish you the best of luck!</strong></p>
        <p>Thank you.</p>
    @else
        <p>Invalid template selected.</p>
    @endif

    <div class="signature">
        <p>Yours faithfully,</p>
        <p>For <strong>Xsilica Software Solutions Pvt. Ltd.</strong></p>
        <p><strong>{{ $signatoryName }}</strong></p>
        <img src="data:image/jpeg;base64,{{ $signatorySignature  }}" alt="Signature" style="width:150px; height:auto;">
        <p><strong>{{ $signatoryDesignation  }}</strong></p>
    </div>
    <div class="cc">
        <p><strong>Cc:</strong> Reporting Manager, Personal File</p>
    </div>

    <!-- To separate letters if needed, you can add a page break here -->
    <div style="page-break-before: always;"></div>
</div>
