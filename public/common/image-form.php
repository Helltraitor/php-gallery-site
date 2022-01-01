<?php declare(strict_types=1) ?>

<div class="row d-flex justify-content-center align-items-center my-2">
    <div class="col-12 col-md-8 col-lg- col-xl-8">
        <div class="card-body p-4 text-center bg-dark" style="border-radius: 1rem;">
            <form method="post" action="/person" oninput="fileTrigger()" enctype="multipart/form-data">
                <div>
                    <div class="input-group align-items-start mb-3">
                        <input type="file" name="file" oninput="fileFileTrigger()" id="fileFile" class="form-control"/>
                        <button class="btn btn-outline-light px-5 disabled" id="fileConfirm" type="submit">Add</button>
                    </div>
                    <div class="form-floating">
                        <!-- Text area eat any space between tags. Don't move php code inside it. -->
                        <textarea type="text" name="description" id="fileDescription" class="form-control" placeholder="Description" style="resize: none; min-height: 5em;"><?=$this->description?></textarea>
                        <label class="form-label text-dark" for="fileDescription" style="font-size: small;">Description</label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>