<?php declare(strict_types=1) ?>

<div class="row d-flex justify-content-center align-items-center my-2">
    <div class="col-12 col-md-8 col-xl-8">
        <div class="card-body p-4 text-center bg-dark" style="border-radius: 1rem;">
            <div class="row">
                <div class="col-lg-6">
                    <div class="container flex-fill">
                        <div id="imagePlaceHolder"></div>
                    </div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <div class="container flex-fill">
                        <div class="form-inline mb-2">
                            <div id="star1" class="fa fa-star text-secondary"></div>
                            <div id="star2" class="fa fa-star text-secondary"></div>
                            <div id="star3" class="fa fa-star text-secondary"></div>
                            <div id="star4" class="fa fa-star text-secondary"></div>
                            <div id="star5" class="fa fa-star text-secondary"></div>
                            <span id="rating" class="text-secondary ms-2">0 : 0</span>
                        </div>
                        <?php if (!str_contains('self other', $this->type)) {
                            include_once __DIR__ . '/../common/image-author.html';
                        } ?>
                        <div class="mb-2">
                            <!-- Text area eat any space between tags. Don't move php code inside it. -->
                            <textarea type="text" id="description" class="form-control bg-dark text-secondary btn-outline-secondary" disabled style="resize: none; min-height: 6em;"></textarea>
                            <label for="description"></label>
                        </div>
                        <div class="mb-2">
                            <div class="btn-group">
                                <button id="prevButton" onclick="imageData.prev()" class="btn btn-secondary disabled">Prev</button>
                                <button id="nextButton" onclick="imageData.next()" class="btn btn-secondary disabled">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once __DIR__ . '/../js/image.php';