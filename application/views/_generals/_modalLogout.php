<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Are you sure ?</h4>
            </div>
            <div class="modal-body">
                Choose "Log Out" below if you are ready to end your session now.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"> Cancel </button>
                <a href="<?= url('auth/logout') ?>" type="button" class="btn btn-danger pull-right"> Log Out </a>
            </div>
        </div>
    </div>
</div>