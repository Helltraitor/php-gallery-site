<?php declare(strict_types=1) ?>

<script>
    // THIS SCRIPT IS LOADED ONLY IF USER HAVE UPLOADED IMAGES

    const Stars = [];
    for (let j = 1; j < 6; j++)
    {
        Stars.push(document.getElementById("star" + String(j)));
    }

    function flushStarEffects(star) {
        star.classList.remove('text-warning');
        star.classList.remove('text-info');
        star.classList.remove('text-success');
        star.classList.remove('text-secondary');
    }

    function setRating(score) {
        for (let j = 0; j < 5; j++)
        {
            let star = Stars[j];
            flushStarEffects(star);
            if (j + 1 <= score + 0.5) {
                star.classList.add('text-success');
            } else {
                star.classList.add('text-secondary');
            }
        }
    }

    function clickStar(id) {
        return () => {
            const rated = imageData.isRated();
            for (let j = 0; USER && !imageData.isUserImage() && !rated && j < 5; j++)
            {
                let star = Stars[j];
                flushStarEffects(star);
                if (j + 1 <= id) {
                    star.classList.add('text-info');
                } else {
                    star.classList.add('text-secondary');
                }
            }
            if (!rated)
                imageData.setRated(id);
        }
    }

    function inStar(id) {
        return () => {
            const rated = imageData.isRated();
            for (let j = 0; USER && !imageData.isUserImage() && !rated && j < 5; j++)
            {
                let star = Stars[j];
                flushStarEffects(star);
                if (j + 1 <= id) {
                    star.classList.add('text-warning');
                } else {
                    star.classList.add('text-secondary');
                }
            }
        };
    }

    function outStar() {
        return () => {
            const rated = imageData.isRated();
            for (let j = 0; USER && !imageData.isUserImage() && !rated && j < 5; j++)
            {
                let star = Stars[j];
                flushStarEffects(star);
                if (j + 1 <= imageData.getScore() + 0.5) {
                    star.classList.add('text-success');
                } else {
                    star.classList.add('text-secondary');
                }
            }
        };
    }


    const USER = <?=$this->user?>;


    class ImageButton {
        constructor(element_id) {
            this.element = document.getElementById(element_id);
        }

        lock() {
            this.element.classList.add('disabled');
        }
        unlock() {
            this.element.classList.remove('disabled');
        }
    }


    const nextButton = new ImageButton('nextButton');
    const prevButton = new ImageButton('prevButton');


    class Image {
        constructor(target_id, target_name, id, description, rating, rated, isRatedByUser) {
            this.id = id;
            this.description = description;
            this.rating = rating;
            this.rated = rated;
            this.score = rated ? rating / rated : 0;
            this.isRatedByUser = isRatedByUser;
            this.userRating = 0;
            this.target_id = target_id;
            this.target_name = target_name;
            this.element = null;
            this.element_id = `U${target_id}I${id}`;

            this.__load();
        }

        __load() {
            this.element = document.createElement('img');
            this.element.setAttribute( 'id', this.element_id);
            this.element.setAttribute( 'hidden', 'true');
            this.element.setAttribute( 'src', `/image/${this.target_id}/${this.id}`);
            this.element.setAttribute( 'style', "border-radius: 1rem; max-height: 320px; max-width: 320px;");
            IPH.appendChild(this.element);
        }

        hide() {
            this.element.hidden = true;
        }

        show() {
            this.element.hidden = false;

            if (author) {
                author.innerText = this.target_name;
                author.href = `/person/${this.target_id}`;
            }
            description.value = this.description;

            let score = String(this.score);
            if (score.length > 3)
                score = score.slice(0, 3);
            rating.innerText = `${score} : ${this.rated}`;

            setRating(this.score);
        }
    }


    class ImageData {
        constructor() {
            // id: 4, rating: 0, rated: 0, description: "", rated_users: "{}"
            this.data = <?=$this->images?>;
            this.images = [];
            this.pointer = 0;

            this.__init();
        }

        __init() {
                if (this.data.length === 0) {
                    return;
                }
                if (this.data.length > 1) {
                    nextButton.unlock();
                }
                this.__load_image(this.pointer);
                this.images[this.pointer].show();
        }

        __load_image(pointer) {
            const regexp = new RegExp(`([{s])${USER}([,}])`);
            let source = this.data[pointer];
            let image = new Image(
                source["user"],
                source["name"],
                source["id"],
                source["description"],
                source["rating"],
                source["rated"],
                USER !== source["user"] && regexp.test(source["rated_users"])
            )
            this.images.push(image);
        }

        __sendRating(image, rating_) {
            const form = new FormData();
            form.append('ID', image.id);
            form.append('RATING', rating_);

            let request = new XMLHttpRequest();
            request.open('POST', `/person/${image.target_id}`, true);
            request.send(form);
        }

        isUserImage() {
            return imageData.images[imageData.pointer].target_id === USER;
        }

        getScore() {
            return imageData.images[imageData.pointer].score;
        }

        isRated() {
            return imageData.images[imageData.pointer].isRatedByUser;
        }

        next() {
            if (this.pointer + 2 >= this.data.length)
                nextButton.lock();

            if (this.pointer + 1 < this.data.length) {
                if (this.pointer + 1 > 0)
                    prevButton.unlock();
                if (this.pointer + 1 >= this.images.length)
                    this.__load_image(this.pointer + 1);

                let current = this.images[this.pointer];
                let next = this.images[this.pointer + 1];
                current.hide();
                next.show();
                this.pointer++;
            }
        }

        prev() {
            if (this.pointer - 2 < 0)
                prevButton.lock();

            if (this.pointer - 1 >= 0) {
                if (this.pointer - 1 < this.data.length)
                    nextButton.unlock();

                let current = this.images[this.pointer];
                let prev = this.images[this.pointer - 1];
                current.hide();
                prev.show();
                this.pointer--;
            }
        }

        setRated(rating_) {
            let image = imageData.images[imageData.pointer];
            image.rated++;
            image.rating += rating_;
            image.score = image.rating / image.rated;
            image.isRatedByUser = true;

            let score = String(image.score);
            if (score.length > 3)
                score = score.slice(0, 3);
            rating.innerText = `${score} : ${image.rated}`;

            this.__sendRating(image, rating_);
        }
    }

    const author = document.getElementById('author');
    const description = document.getElementById('description');
    const IPH = document.getElementById('imagePlaceHolder');
    const rating = document.getElementById('rating');
    const imageData = new ImageData();

    for (let id = 1; id < 6; id++) {
        let star = document.getElementById('star' + String(id));
        star.addEventListener('click', clickStar(id));
        star.addEventListener('mouseover', inStar(id));
        star.addEventListener('mouseout', outStar(id));
    }
</script>