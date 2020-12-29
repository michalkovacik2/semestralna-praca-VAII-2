class Paginator
{
    constructor(numberOfData, itemsOnOnePage)
    {
        let numSites = Math.floor(numberOfData/itemsOnOnePage);
        this._numberOfPages = numberOfData % itemsOnOnePage !== 0 ? numSites + 1 : numSites;
    }

    displayPages(link = null)
    {
        var o = document.getElementById("paginator");
        let html = `<ul class="pagination justify-content-center mt-3">`;
        for (let i = 0; i < this._numberOfPages; i++)
        {
            html += `<li class="page-item"><a id="paginator`+ (i+1) +`" class="page-link" href="`+(link == null? "#": link )+`">` + (i+1) + `</a></li>`;
        }
        html += `</ul>`;
        o.innerHTML = html;
    }

    getNumberOfPages()
    {
        return this._numberOfPages;
    }
}
