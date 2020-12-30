/**
 * Main class of reservation site
 */
class Reservation
{
    /**
     * Constructor
     */
    constructor()
    {
        this._books = new Map();
        this._genres = new Map();
        this._filter = null;
        this._page = 1;
        this._searchBar = null;
        this._admin = false;

        this.reloadData();
        this.reloadBookCount();

        let self = this;
        //detect input on search bar
        $("#searchBar").on("input", () =>
        {
            clearTimeout(self._searchTimer);
            self._searchTimer = setTimeout(() => { self.handleSearchBar(); }, 500);
        });

        setInterval(() => { this.reloadData(); }, 9000);
        setInterval(() => { this.reloadBookCount(); }, 3000);
    }

    /**
     * ASYNC method that gets all the book and also send filter paraeters
     * @returns {Promise<void>}
     */
    async getBooks()
    {
        try
        {
            let link = "semestralka?c=Reserve&a=books&page=" + this._page;
            link += this._filter == null ? "" : "&filter=" + this._filter;
            link += this._searchBar == null ? "" : "&like=" + this._searchBar;
            let response = await fetch(link);
            let data = await response.json();

            let count =  data[0].ALL;
            this._admin = data[0].admin;
            data.shift();

            let map = new Map();
            data.forEach((book) =>
            {
                map.set(book.ISBN, new Book(book));
            });

            //If there is something changed in data then update html.
            if (!compareMapsOfBooks(map, this._books))
            {
                this._books = map;
                let books = document.getElementById("books");
                let html = "";
                this._books.forEach((book) =>
                {
                    html += book.generateHtml(this._admin);
                });
                books.innerHTML = html;

                let self = this;
                //If admin then create click on edit
                if (this._admin)
                {
                    $('.adminEdit').on('click', (event) =>
                    {
                        self.handleEditBook(event);
                    })
                }

                //Create paginator
                let paginator = new Paginator(count, 5);
                paginator.displayPages();
                for (let i=0; i < paginator.getNumberOfPages(); i++)
                {
                    $('#paginator'+(i+1)).on('click', (event) =>
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

    /**
     * ASYNC method that gets genres and books counts for them
     * @returns {Promise<void>}
     */
    async getGenres()
    {
        try
        {
            let response = await fetch("semestralka?c=Reserve&a=genres");
            let data = await response.json();

            let newGenres = new Map();
            data.forEach((genre) =>
            {
                let genreObj = new Genre(genre);
                newGenres.set(genreObj.genre_id, genreObj);
            });

            //If there is something changed in data then update html.
            if (!compareMapsOfBooks(newGenres, this._genres))
            {
                this._genres = newGenres;
                let genres = document.getElementById("genres");
                let html = `<li class="list-group-item d-flex justify-content-between align-items-center categoryTitle">
                                <strong>Žánre</strong> ` + (this._admin ? '<i class="fas fa-plus genrePlus" id="addGenre"></i>' : '' ) + `
                            </li>`;
                this._genres.forEach((genre) =>
                {
                    html += genre.generateHtml(this._admin);
                });
                genres.innerHTML = html;

                let self = this;
                this._genres.forEach((genre) =>
                {
                    if (genre.genre_id !== "")
                    {
                        $('#radioEdit'+genre.genre_id).on('click', (event) =>
                        {
                            self.handleEditGenre(event, genre.name);
                        });
                    }
                });

                $( '#addGenre' ).on('click', () =>
                {
                    self.handleAddGenre();
                });

                $( ':radio' ).on('click', (event) =>
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

    /**
     * ASYNC method that gets the number of available books
     * @returns {Promise<void>}
     */
    async getNumberOfAvailableBooks()
    {
        try
        {
            let link = "semestralka?c=Reserve&a=countBook";
            link += this._filter == null ? "" : "&filter=" + this._filter;
            let response = await fetch(link);
            let data = await response.json();

            let bookCountMap = new Map();

            let self = this;
            data.forEach((bookCount) =>
            {
                bookCountMap.set(bookCount.ISBN, bookCount.count);
            });

            for (let [key, val] of this._books)
            {
                let html = "";
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
                document.getElementById("count"+ key).innerHTML = html;
                $('#reserve'+key).on('click', (event) =>
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

            let dataResponse = await response.text();
            let responseJson = JSON.parse(dataResponse);
            let textToShow = "";
            if (responseJson.Error === "")
            {
                textToShow = "Rezervácia knihy prebehla úspešne";
            }
            else
            {
                textToShow = "Chyba pri rezervovaní knihy <br>" + responseJson.Error;
            }

            let popUp = new InfoPopUp(textToShow);

            if (responseJson.Error === "")
                popUp.setSuccess();
            else
                popUp.setAlert();

            popUp.show();
            this.getNumberOfAvailableBooks();

        } catch (e)
        {
            console.error('Chyba: ' + e.message);
        }
    }

    /**
     * ASYNC method that is used to create or update genre
     * @param id - id of genre or null if not set
     * @param name - entered name
     * @returns {Promise<void>}
     */
    async modifyGenre(id, name)
    {
        try {
            id = id == null ? -1 : id;
            let response = await fetch("semestralka?c=Reserve&a=modifyGenre", {
                method: 'POST', // or 'PUT'
                headers:
                    {
                        'Content-Type': "application/json",
                    },
                body: JSON.stringify(
                    {
                        genre_id: id,
                        name: name
                    })
            });

            let dataResponse = await response.text();
            let responseJson = JSON.parse(dataResponse);
            console.log(responseJson.Error);
            if (responseJson.Error === "")
            {
                $('#modalGenre').modal('hide');
            }
            else
            {
                let errors = document.getElementById('modalGenreErrors');
                errors.innerHTML = "Žáner sa nepodarilo pridať <br>" + responseJson.Error;
                errors.classList.remove("d-none");
            }
            this.getGenres();
            this.getBooks();

        } catch (e)
        {
            console.error('Chyba: ' + e.message);
        }
    }

    /**
     * Handler for click on radio buttons
     * @param event
     */
    handleClick(event)
    {
        let text = event.toElement.id.replace("radio", "");
        this._filter = text === "" ? null : text;
        this._page = 1;
        this.getBooks();
    }

    /**
     * Handler for click on page numbers
     * @param event
     */
    handleClickPaginator(event)
    {
        let text = event.toElement.id.replace("paginator", "");
        this._page = text === "" ? null : text;
        this.getBooks();
    }

    /**
     * Handler for click on reserve buttons
     * @param event
     */
    handleClickReserve(event)
    {
        let text = event.toElement.id.replace("reserve", "");
        let ISBN = text === "" ? null : text;
        this.sendReservation(ISBN);
    }

    /**
     * Handler for input from search bar
     */
    handleSearchBar()
    {
        this._page = 1;
        this._searchBar = $("#searchBar").val();
        this.getBooks();
    }

    /**
     * Handler for clicking on editing book
     * @param event
     */
    handleEditBook(event)
    {
        let text = event.toElement.id.replace("adminEdit", "");
        $("#editBookFormISBN").val(text);
        $("#editBookFormID").submit();
    }

    /**
     * handler for add new genre
     */
    handleAddGenre()
    {
        document.getElementById('modalGenreTitle').innerText = "Pridajte nový žáner";
        document.getElementById('modalGenreName').value = "";
        document.getElementById('modalGenreErrors').classList.add("d-none");
        let $button = $('#modalGenreButton');
        $button.text("Pridaj");
        $('#modalGenre').modal('show');

        let self = this;
        $button.off();
        $button.on('click', () =>
        {
            name = $('#modalGenreName').val();
            self.modifyGenre(null, name);
        });
    }

    /**
     * Handler for editing genre
     * @param event
     * @param name
     */
    handleEditGenre(event, name)
    {
        let id  = event.toElement.id.replace("radioEdit", "");
        document.getElementById('modalGenreTitle').innerText = "Upravte žáner";
        document.getElementById('modalGenreName').value = name;
        document.getElementById('modalGenreErrors').classList.add("d-none");
        let $button = $('#modalGenreButton');
        $button.text("Uprav");
        $('#modalGenre').modal('show');

        let self = this;
        $button.off();
        $button.on('click', () =>
        {
            name = $('#modalGenreName').val();
            self.modifyGenre(id, name);
        });
    }

    /**
     * Method used to update data
     */
    reloadData()
    {
        this.getBooks();
        this.getGenres();
    }

    /**
     * Method used to update book count
     */
    reloadBookCount()
    {
        this.getNumberOfAvailableBooks();
    }
}

/**
 * Represents book
 */
class Book
{
    /**
     * Constructor
     * @param json - data in json format
     */
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

    /**
     * It is used to generate html
     * @param admin - true if it is admin false otherwise
     * @returns {string}
     */
    generateHtml(admin)
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
                                                <h4>` + this.name + `<span class="adminEdit adminControls">` + (admin ? `<i class="fas fa-pen ml-2" id="adminEdit` + this.ISBN + `"></i>` : "") + `</span> </h4>
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

    /**
     * Used to compare two Books
     * @param other
     * @returns {boolean}
     */
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

/**
 * Represents genre
 */
class Genre
{
    /**
     * Constructor
     * @param json - input data in json format
     */
    constructor(json)
    {
        this._genre_id = json.genre_id;
        this._name = json.name;
        this._count = json.count;
    }

    /**
     * Method used to generate html
     * @param admin
     * @returns {string}
     */
    generateHtml(admin)
    {
        var html = "";
        html += `<li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="radio`+ this.genre_id + `" name="category" ` + (this.genre_id === "" ? "checked" : "" ) + `>
                <label class="custom-control-label" for="radio` + this.genre_id + `">` + this.name + `</label>
                ` +( admin && this._genre_id != null ? '<i class="fas fa-pen ml-2 adminControls" id="radioEdit' + this._genre_id + '"></i>' : '') + `
            </div>
            <span class="badge orangeBadge">` + this.count + `</span>
        </li>`;
        return html;
    }

    /**
     * Compare two genres
     * @param other
     * @returns {boolean}
     */
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

/**
 * Compare two maps and their values
 * @param map1
 * @param map2
 * @returns {boolean}
 */
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

document.addEventListener('DOMContentLoaded', () => { new Reservation(); }, false);
