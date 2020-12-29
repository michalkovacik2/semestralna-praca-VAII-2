class AdminPanel
{
    constructor()
    {
        this._searchBar = null;
        this._page = 1;
        this._searchTimer;

        let self = this;
        $("#searchBar").on("input", () =>
        {
            clearTimeout(self._searchTimer);
            self._searchTimer = setTimeout(function() { self.handleSearchBar(); }, 200);
        });

        this.getData();
        this.reloadData();
        setInterval(() => {
            this.reloadData()
        }, 2000);
    }

    async getData()
    {
        try
        {
            let link = "semestralka?c=AdminPanel&a=userReservation&page=" + this._page;
            link += (this._searchBar == null ? "" : "&like=" + this._searchBar);
            let response = await fetch(link);
            let data = await response.json();

            let count =  data[0].ALL;
            data.shift();

            let header =  data[0];
            data.shift();

            let html = "<tr>";
            for (var i = 0; i < header.length; i++)
            {
                html += "<th>" + header[i] + "</th>";
            }
            html += "</tr>";

            let rows = [];

            data.forEach((tableRow) =>
            {
                let row = new TableRow(tableRow);
                rows.push(row);
                html += row.generateHTML();
            });
            document.getElementById('adminPanelTable').innerHTML = html;

            rows.forEach((row) =>
            {
                row.setOnclick(this);
            });

            //Create paginator
            let self = this;
            let paginator = new Paginator(count, 10);
            paginator.displayPages('#searchBar');
            for (let i=0; i < paginator.getNumberOfPages(); i++)
            {
                $('#paginator'+(i+1)).on('click', (event) =>
                {
                    self.handleClickPaginator(event);
                });
            }

        }
        catch (e)
        {
            console.error('Error: ' + e.message);
        }
    }

    async sendAction(command, id) {
        try {
            let response = await fetch("semestralka?c=AdminPanel&a=modify" , {
                method: 'POST', // or 'PUT'
                headers:
                    {
                        'Content-Type': "application/json",
                    },
                body: JSON.stringify(
                    {
                        reservation_id: id,
                        command: command,
                    })
            });

            let text = "";
            if (command === "delete")
            {
               text = "Rezervácia bola úspešne zrušená";
            }
            else if (command === "lend")
            {
                text = "Kniha bola úspešne vydaná";
            }
            else if (command === "return")
            {
                text = "Kniha je zaevidovaná ako vrátená";
            }

            let dataResponse = await response.text();
            let responseJson = JSON.parse(dataResponse);
            let popUp = new InfoPopUp();
            if (responseJson.Error === "")
            {
                popUp.setSuccess(text);
                popUp.show();
            }
            else
            {
                popUp.setAlert("Došlo k chybe: <br>" + responseJson.Error);
                popUp.show();
            }

        } catch (e)
        {
            console.error('Chyba: ' + e.message);
        }
    }

    reloadData()
    {
        this.getData();
    }

    handleClickPaginator(event)
    {
        let text = event.toElement.id.replace("paginator", "");
        this._page = text === "" ? null : text;
        this.getData();
    }

    handleSearchBar()
    {
        this._page = 1;
        this._searchBar = $("#searchBar").val();
        this.getData();
    }

    handleButtonDelete(id)
    {
        this.sendAction("delete", id);
    }

    handleButtonLend(id)
    {
        this.sendAction("lend", id);
    }

    handleButtonReturn(id)
    {
        this.sendAction("return", id);
    }
}

class TableRow
{
    constructor(json)
    {
        this._reservation_id = json.reservation_id;
        this._ISBN = json.ISBN;
        this._name = json.name;
        this._book_id = json.book_id;
        this._email = json.email;
        this._request_date = this.formatDate(json.request_date);
        this._reserve_day = this.formatDate(json.reserve_day);
        this._return_day = this.formatDate(json.return_day);
    }

    generateHTML()
    {
        let html = '';
        html += "<tr>";
        html += "<td>" + this._ISBN +    "</td>" + "<td>" + this._name + "</td>";
        html += "<td>" + this._book_id + "</td>" + "<td>" + this._email + "</td>";
        html += '<td class="text-center">' + this._request_date + ( this._reserve_day == null && this._return_day == null ? '<div class="d-flex justify-content-center mt-1"><button class="btn-danger d-block" id="remove'+ this._reservation_id +'"> Zruš </button></div>' : '' ) + '</td>';
        html += '<td class="text-center">' + (this._reserve_day == null ? 'Neprevzaté <div class="d-flex justify-content-center mt-1"><button class="btn-success d-block" id="lend'+ this._reservation_id +'"> Potvrď </button>' : this._reserve_day) + '</td>' ;
        html += `<td class="text-center">` + (this._return_day == null ? "Nevrátené" : this._return_day) +
                 (this._return_day == null && this._reserve_day != null ?  `<div class="d-flex justify-content-center mt-1"><button class="btn-success d-block" id="return`+ this._reservation_id +`"> Potvrď </button>` : "" ) + '</td>';
        html += "</tr>";
        return html;
    }

    setOnclick(adminPanel)
    {
        if (this._reserve_day == null && this._return_day == null)
        {
            let delButton =  document.getElementById('remove'+this._reservation_id);
            delButton.addEventListener('click', () =>
            {
                adminPanel.handleButtonDelete(this._reservation_id);
            });
        }
        if (this._reserve_day == null)
        {
            let lendButton = document.getElementById('lend'+this._reservation_id);
            lendButton.addEventListener('click', () =>
            {
                adminPanel.handleButtonLend(this._reservation_id);
            });
        }

        if (this._return_day == null && this._reserve_day != null)
        {
            let returnButton = document.getElementById('return'+this._reservation_id);
            returnButton.addEventListener('click', () =>
            {
                adminPanel.handleButtonReturn(this._reservation_id);
            });
        }
    }

    formatDate(date)
    {
        if (date == null)
            return null;

        let dateObj = new Date(date);
        return dateObj.getDate().toString() + '.' + (dateObj.getMonth()+1).toString() + '.' + dateObj.getFullYear().toString();
    }
}

document.addEventListener('DOMContentLoaded', () =>
{
    let adminPanel = new AdminPanel();
}, false);
