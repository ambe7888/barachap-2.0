<!-- Bootstrap Modal -->
<div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileModalLabel">File Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be dynamically added here -->
                <img id="filePreview" src="" class="img-fluid" style="display: none; max-width: 100%;" />
                <a id="fileDownload" href="" download class="btn btn-primary" style="display: none;">Download File</a>
            </div>
        </div>
    </div>
</div>
