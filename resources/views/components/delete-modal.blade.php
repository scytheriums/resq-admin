<div class="modal fade modal-danger" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-transparent">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body px-sm-5 mx-50 pb-5">
          <h5 class="text-center mb-1" id="deleteModalTitle">Apakah Anda Yakin?</h5>
          <div class="text-center mt-2">
            Apakah Anda yakin ingin menghapus <span class="delete-type"></span> <span class="delete-hint badge bg-danger"></span>? 
            <br>
            Tindakan ini tidak dapat dibatalkan.
          </div>

          <form class="row gy-1 gx-2 mt-75 deleteModalForm" method="post">
            @method('DELETE')
            @csrf
            <div class="col-12 text-center">
              <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Batal">Batal</button>
              <button type="submit" class="btn btn-danger me-1 mt-1 btn-confirm-delete">Hapus</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
<div class="modal fade modal-danger" id="deleteMultipleModal" tabindex="-1" aria-labelledby="deleteMultipleModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-transparent">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body px-sm-5 mx-50 pb-5">
        <h5 class="text-center mb-1" id="deleteMultipleModalTitle">Apakah Anda Yakin?</h5>
        <div class="text-center mt-2">
          Apakah Anda yakin ingin menghapus item yang dipilih? 
          <br>
          Tindakan ini tidak dapat dibatalkan.
        </div>

        <form class="row gy-1 gx-2 mt-75 deleteMultipleModalForm" method="post">
          @csrf
          <input type="hidden" name="ids">
          <div class="col-12 text-center">
            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            <button type="button" class="btn btn-danger me-1 mt-1 btn-confirm-delete-multiple">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
