<!-- Country Edit Modal -->
<div class="modal fade" id="coverLetterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Cover Letter') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <div class="single-input mb-3">
                        <p id="coverLetterContent"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cmnBtn btn_5 btn_bg_danger radius-5" data-bs-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                </div>
        </div>
    </div>
</div>
