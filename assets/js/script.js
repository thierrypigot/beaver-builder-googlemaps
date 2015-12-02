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

var styles = [
    {
        stylers: [
            {hue: ""},
            {saturation: "-50"},
            {lightness: "-3"},
        ]
    },
    {
        featureType: "landscape", stylers: [
        {visibility: "on"},
        {hue: ""},
        {saturation: ""},
        {lightness: ""},
    ]
    },
    {
        featureType: "administrative", stylers: [
        {visibility: "on"},
        {hue: ""},
        {saturation: ""},
        {lightness: ""},
    ]
    },
    {
        featureType: "road", stylers: [
        {visibility: "on"},
        {hue: ""},
        {saturation: ""},
        {lightness: ""},
    ]
    },
    {
        featureType: "water", stylers: [
        {visibility: "on"},
        {hue: ""},
        {saturation: ""},
        {lightness: ""},
    ]
    },
    {
        featureType: "poi", stylers: [
        {visibility: "on"},
        {hue: ""},
        {saturation: ""},
        {lightness: ""},
    ]
    },
];

var styledMap = new google.maps.StyledMapType( styles, {name: "Routier"} );
