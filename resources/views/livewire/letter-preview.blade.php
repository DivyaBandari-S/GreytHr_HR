<div>
    @if ($previewLetter)
        <div class="preview-section">
           
            <div class="letter-preview">
                <!-- Include the letter template for the current employee -->
                @include('letter_template', [
                    'template_name' => $previewLetter['template_name'],
                    'ctc' => $previewLetter['ctc'],
                    'authorized_signatory' => $previewLetter['authorized_signatory'],
                    'employee' => $currentEmployee, 
                ])
            </div>

            <!-- Pagination Buttons -->
            <div class="navigation-buttons">
                <div class="pagination-controls">
                    @if ($currentEmployeeIndex > 0)
                        <button wire:click="previousEmployee" class="submit-btn">Previous</button>
                    @endif

                    @if ($currentEmployeeIndex < count($previewLetter['employees']) - 1)
                        <button wire:click="nextEmployee" class="submit-btn">Next</button>
                    @endif
                </div>
            </div>

            <!-- Display current employee page number -->
            <p class="page-number">Page {{ $currentEmployeeIndex + 1 }} of {{ count($previewLetter['employees']) }}</p>
        </div>
    @endif


<style>
    .preview-section {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .letter-preview {
        margin-bottom: 20px;
    }

    .navigation-buttons {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination-controls {
        display: flex;
        gap: 15px;
    }

    .prev-button, .next-button {
        padding: 10px 20px;
        font-size: 14px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .prev-button:hover, .next-button:hover {
        background-color: #0056b3;
    }

    .page-number {
        text-align: center;
        font-size: 16px;
        margin-top: 10px;
        font-weight: bold;
    }
</style>
</div>

