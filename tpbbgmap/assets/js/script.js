// This function picks up the click and opens the corresponding info window
function myClick(i)
{
    google.maps.event.trigger(gmarkers[i], "click");
}

function the_content( marker )
{
    if( marker.content )
    {
        return '<div class="infoBox"><div class="item-data">' + marker.content + '</div></div>';
    }
}