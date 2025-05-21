<div class="modal fade" id="cardPreviewModal" tabindex="-1" aria-labelledby="cardPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cardPreviewModalLabel">Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                  <div class="card-overlay"></div>
                  <div class="card-details">
                    <header>
                        <span class="logo">
                            <img src="assets/images/lions/1.png" alt="" />
                            <h5>Master Card</h5>
                        </span>
                        <img src="assets/images/chip.png" alt="" class="chip" />
                    </header>
                    <div class="name-number">
                        <h6>Card Number</h6>
                        <h5 class="number">8050 5040 2030 3020</h5>
                        <h5 class="name">Prem Kumar Shahi</h5>
                    </div>
                    <div class="valid-date">
                        <h6>Valid Thru</h6>
                        <h5>05/28</h5>
                    </div>
                  </div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div> -->
        </div>
    </div>
</div>

<style>
#cardPreviewModal .card-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  border-radius: 28px;
}
#cardPreviewModal .container {
    position: relative;
    background-image: url("assets/images/cards/2.png");
    background-size: cover;
    padding: 20px;
    border-radius: 28px;
    width: 100%;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
}
#cardPreviewModal header {
  padding-bottom: 15px;
}
#cardPreviewModal header,
#cardPreviewModal .logo {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
#cardPreviewModal .logo img {
  width: 48px;
  margin-right: 10px;
}
#cardPreviewModal .modal-body h5 {
  font-size: 16px;
  font-weight: 400;
  color: #fff;
}
#cardPreviewModal header .chip {
  width: 60px;
}
#cardPreviewModal .modal-body h6 {
  color: #fff;
  font-size: 10px;
  font-weight: 400;
}
#cardPreviewModal h5.number {
  margin-top: 4px;
  font-size: 18px;
  letter-spacing: 1px;
}
#cardPreviewModal h5.name {
  margin-top: 20px;
}
#cardPreviewModal .container .card-details {
  /* margin-top: 40px;
  display: flex;
  justify-content: space-between;
  align-items: flex-end; */
  position: relative;
  font-size: 18px;
  font-weight: 500;
}
</style>