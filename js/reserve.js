class Reservation
{
    constructor()
    {
        this._books = new Map();
        this._genres = new Map();
        this._filter = null;
        this._page = 1;
        this._searchBar = null;

        this.reloadData();
        this.reloadBookCount();

        let self = this;
        $("#searchBar").on("input", function(event)
        {
            self.handleSearchBar(event);
        });

        setInterval(() => {
            this.reloadData()
        }, 10000);

        setInterval(() => {
            this.reloadBookCount()
        }, 3000);
    }

    async getBooks()
    {
        try
        {
            let link = this._filter == null ? "semestralka?c=Reserve&a=books&page=" + this._page : "semestralka?c=Reserve&a=books&filter=" + this._filter + "&page=" + this._page;
            link += this._searchBar == null ? "" : "&like=" + this._searchBar;
            let response = await fetch(link);
            let data = await response.json();
            let count =  data[0].ALL;
            data.shift();

            let map = new Map();
            data.forEach((book) =>
            {
                var bookObj = new Book(book);
                map.set(book.ISBN, bookObj);
            });

            //If there is something changed in data then update html.
            if (!compareMapsOfBooks(map, this._books))
            {
                this._books = map;
                let books = document.getElementById("books");
                let html = "";
                this._books.forEach((book) =>
                {
                    html += book.generateHtml();
                });
                books.innerHTML = html;

                //Create paginator
                let paginator = new Paginator(count);
                paginator.displayPages();
                let self = this;
                for (let i=0; i < paginator.getNumberOfPages(); i++)
                {
                    $('#paginator'+(i+1)).on('click', function(event)
                    {
                        self.handleClickPaginator(event);
                    });
                }
                this.getNumberOfAvailableBooks();
            }
        }
        catch (e)
        {
            console.error('Error: ' + e.message);
        }
    }

    async getGenres()
    {
        try
        {
            let response = await fetch("semestralka?c=Reserve&a=genres");
            let data = await response.json();
            let change = false;

            //Get new data and check differences
            data.forEach((genre) =>
            {
                let genreObj = new Genre(genre);
                if (this._genres.has(genreObj.genre_id))
                {
                    if (!this._genres.get(genreObj.genre_id).equals(genreObj))
                    {
                        this._genres.set(genreObj.genre_id, genreObj);
                        change = true;
                    }
                }
                else
                {
                    this._genres.set(genreObj.genre_id, genreObj);
                    change = true;
                }
            });

            //If there is something changed in data then update html.
            if (change)
            {
                let genres = document.getElementById("genres");
                let html = `<li class="list-group-item d-flex justify-content-between align-items-center categoryTitle">
                                <strong>Žánre</strong>
                            </li>`;
                this._genres.forEach((genre) =>
                {
                    html += genre.generateHtml();
                });
                genres.innerHTML = html;
                let self = this;
                $( ':radio' ).on('click', function(event)
                {
                    self.handleClick(event);
                });
            }
        }
        catch (e)
        {
            console.error('Error: ' + e.message);
        }
    }

    async getNumberOfAvailableBooks()
    {
        try
        {
            let link = this._filter == null ? "semestralka?c=Reserve&a=countBook" : "semestralka?c=Reserve&a=countBook&filter=" + this._filter;
            let response = await fetch(link);
            let data = await response.json();

            let bookCountMap = new Map();

            let self = this;
            data.forEach((bookCount) => { bookCountMap.set(bookCount.ISBN, bookCount.count); });
            for (let [key, val] of this._books)
            {
                let o = document.getElementById("count"+ key);
                let html;
                let count = bookCountMap.get(key);
                if (count === undefined)
                {
                    html = `<i class="fas fa-times-circle nedostupneIcon"></i> Nedostupné
                            <a href="#" class="btn buttonUnavailable float-right disabled">Rezervovať</a>`;
                }
                else
                {
                    html = `<i class="fas fa-check-circle dostupneIcon"></i> Dostupné ` + count + ` ks
                            <a id="reserve` + key + `" href="#" class="btn buttonAvailable float-right">Rezervovať</a>`;
                }
                o.innerHTML = html;
                $('#reserve'+key).on('click', function(event)
                {
                    self.handleClickReserve(event);
                });
            }
        }
        catch (e)
        {
            console.error('Error: ' + e.message);
        }
    }

    /**
     * ASYNC method that is used to reserve a particular book from library.
     * @param ISBN
     */
    async sendReservation(ISBN) {
        try {
            let response = await fetch("semestralka?c=Reserve&a=reserveBook", {
                method: 'POST', // or 'PUT'
                headers:
                {
                    'Content-Type': "application/json",
                },
                body: JSON.stringify(
          {
                    ISBN: ISBN,
                })
            });

            //<i class="fas fa-check-circle logoModal"></i>
            let dataResponse = await response.text();
            let responseJson = JSON.parse(dataResponse);
            let modalTEXT = document.getElementById('modalTEXT');
            let modalIcon = document.getElementById('modalIcon');
            let modalButton = document.getElementById('modalButton');
            if (responseJson.Error === "")
            {
                modalTEXT.innerHTML = "<b>Rezervácia knihy prebehla úspešne<b>";
                modalIcon.innerHTML = `<i class="fas fa-check-circle logoModal"></i>`;
                modalIcon.childNodes[0].style.color = 'green';
                modalButton.classList.remove("btn-danger");
                modalButton.classList.add("btn-success");
            }
            else
            {
                modalTEXT.innerHTML = "<b>Chyba pri rezervovaní knihy<b> <br>" + responseJson.Error;
                modalIcon.innerHTML = `<i class="fas fa-times-circle logoModal"></i>`;
                modalIcon.childNodes[0].style.color = 'red';
                modalButton.classList.remove("btn-success");
                modalButton.classList.add("btn-danger");
            }
            $('#modal').modal('show')
            await this.getNumberOfAvailableBooks();

        } catch (e) {
            console.error('Chyba: ' + e.message);
        }
    }

    handleClick(event)
    {
        let text = event.toElement.id.replace("radio", "");
        this._filter = text === "" ? null : text;
        this._page = 1;
        this.getBooks();
    }

    handleClickPaginator(event)
    {
        let text = event.toElement.id.replace("paginator", "");
        this._page = text === "" ? null : text;
        this.getBooks();
    }

    handleClickReserve(event)
    {
        let text = event.toElement.id.replace("reserve", "");
        let ISBN = text === "" ? null : text;
        this.sendReservation(ISBN);
    }

    handleSearchBar(event)
    {
        this._page = 1;
        this._searchBar = $("#searchBar").val();
        this.getBooks()
    }

    reloadData() {
        this.getBooks();
        this.getGenres();
    }

    reloadBookCount()
    {
        this.getNumberOfAvailableBooks();
    }
}

class Book
{
    constructor(json)
    {
        this._ISBN = json.ISBN;
        this._name = json.name;
        this._genre_id = json.genre_id;
        this._author_name = json.author_name;
        this._author_surname = json.author_surname;
        this._release_year = json.release_year;
        this._info = json.info;
        this._picture = json.picture;
        this._genre_name = json.genre_name;
    }

    generateHtml()
    {
        let html = "";
        html += `<div class="jumbotron-fluid bookItem" id="book`+ this.ISBN +`">
                            <div class="container">
                                <div class="row">
                                    <div class="col-xs-2 col-sm-2 col-md-2 paddingZero">
                                        <img src="semestralka/img/books/`+ this.picture + `" class="img-thumbnail itemIcon w-100 h-100" alt="kniha">
                                    </div>
                                    <div class="col-xs-10 col-sm-10 col-md-10 paddingZero">
                                        <div class="card">
                                            <div class="card-body myCard">
                                                <h4>` + this.name + `</h4>
                                                <h6 class="card-subtitle mb-2 text-muted autor">
                                                    ` + this.author_name + " " + this.author_surname + `,<time datetime="` + this.release_year + `"> ` + this.release_year  + ` </time>, (` + this.genre_name + `)
                                                </h6>
                                                <p class="shortInfo"> ` + this.info + ` </p>
                                                <span id="count`+ this.ISBN +`"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
        return html;
    }

    hideElement()
    {
        let element = document.getElementById('book'+this.ISBN);
        element.style.display = 'none';
    }

    showElement()
    {
        let element = document.getElementById('book'+this.ISBN);
        element.style.display = 'block';
    }

    equals(other)
    {
        const keys1 = Object.keys(this);
        const keys2 = Object.keys(other);

        if (keys1.length !== keys2.length) {
            return false;
        }

        for (let key of keys1) {
            if (this[key] !== other[key]) {
                return false;
            }
        }

        return true;
    }

    // region Getters and Setters Book
    get ISBN() {
        return this._ISBN;
    }

    set ISBN(value) {
        this._ISBN = value;
    }

    get name() {
        return this._name;
    }

    set name(value) {
        this._name = value;
    }

    get genre_id() {
        return this._genre_id;
    }

    set genre_id(value) {
        this._genre_id = value;
    }

    get author_name() {
        return this._author_name;
    }

    set author_name(value) {
        this._author_name = value;
    }

    get author_surname() {
        return this._author_surname;
    }

    set author_surname(value) {
        this._author_surname = value;
    }

    get release_year() {
        return this._release_year;
    }

    set release_year(value) {
        this._release_year = value;
    }

    get info() {
        return this._info;
    }

    set info(value) {
        this._info = value;
    }

    get picture() {
        return this._picture;
    }

    set picture(value) {
        this._picture = value;
    }

    get genre_name() {
        return this._genre_name;
    }

    set genre_name(value) {
        this._genre_name = value;
    }

    // endregion
}

class Genre
{
    constructor(json)
    {
        this._genre_id = json.genre_id;
        this._name = json.name;
        this._count = json.count;
    }

    generateHtml()
    {
        var html = "";
        html += `<li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="radio`+ this.genre_id + `" name="category" ` + (this.genre_id === "" ? "checked" : "" ) + `>
                <label class="custom-control-label" for="radio` + this.genre_id + `">` + this.name + `</label>
            </div>
            <span class="badge orangeBadge">` + this.count + `</span>
        </li>`;
        return html;
    }

    equals(other)
    {
        const keys1 = Object.keys(this);
        const keys2 = Object.keys(other);

        if (keys1.length !== keys2.length) {
            return false;
        }

        for (let key of keys1) {
            if (this[key] !== other[key]) {
                return false;
            }
        }

        return true;
    }

    //region Getters and Setters Genre
    get genre_id() {
        return this._genre_id == null ? "" : this._genre_id;
    }

    set genre_id(value) {
        this._genre_id = value;
    }

    get name() {
        return this._name;
    }

    set name(value) {
        this._name = value;
    }

    get count() {
        return this._count;
    }

    set count(value) {
        this._count = value;
    }

    // endregion
}

class Paginator
{
    constructor(numberOfData)
    {
        this._numberOfData = numberOfData;
        let numSites = Math.floor(numberOfData/5);
        this._numberOfPages = numberOfData % 5 !== 0 ? numSites + 1 : numSites;
    }

    displayPages()
    {
        var o = document.getElementById("paginator");
        let html = `<ul class="pagination justify-content-center mt-3">`;
        for (let i = 0; i < this._numberOfPages; i++)
        {
            html += `<li class="page-item"><a id="paginator`+ (i+1) +`" class="page-link" href="#">` + (i+1) + `</a></li>`;
        }
        html += `</ul>`;
        o.innerHTML = html;
    }

    getNumberOfPages()
    {
        return this._numberOfPages;
    }
}

function compareMapsOfBooks(map1, map2)
{
    var testVal;
    if (map1.size !== map2.size) {
        return false;
    }
    for (var [key, val] of map1) {
        testVal = map2.get(key);
        if (testVal === undefined || !testVal.equals(val)) {
            return false;
        }
    }
    return true;
}

document.addEventListener('DOMContentLoaded', () =>
{
    let reservation = new Reservation();

}, false);
