<?php

Class GoogleMap{
    /**
     * @var string
     */
    private $imgFolder;

    /**
     * @var address
     */
    private $address;

    /**
     * @var string
     *
     * Clé de L'API Google
     *
     */
    private $googleAPIKey;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $folder;

    /**
     * @var string
     */
    private $streetviewImage;

    /**
     * @var string
     */
    private $mapImage;

    /**
     * MAP
     * @var integer
     */
    private $zoom;

    /**
     * @var integer
     */
    private $width;

    /**
     * @var integer
     */
    private $height;

    /**
     * STREETVIEW
     * @var integer
     * value 0-90
     * zoom sur l'image
     */
    private $fov;

    /**
     * STREETVIEW
     * @var integer
     * value 0-360
     * orientation de l'image NORD - SUD - EST - OUEST
     */
    private $heading;

    /**
     * @var array
     */

    private $imageNSEW = array();

    /**
     * @var integer
     */
    private $latitude;

    /**
     * @var integer
     */
    private $longitude;

    /**
     * MAP
     * @var string
     * Type de map à afficher :
     * roadmap (default)
     * satellite
     * terrain
     * hybrid
     *
     */
    private $maptype;


    /**
     * @var array
     */
    private $maptypeOptions = array('roadmap','satellite','terrain','hybrid');
    /**
     * @param $googleAPIKEY
     * @param $folder
     */
    public function __construct($googleAPIKEY,$folder){
        $this->googleAPIKey = $googleAPIKEY;
        $this->folder = $folder.'/';
        $this->fov = 90;
        $this->zoom = 15;
        $this->width = 600;
        $this->height = 300;
        $this->latitude = 0;
        $this->longitude = 0;
        $this->maptype = 'satellite';
        $this->filename = uniqid();

    }

    /**
     * Suppression des accents
     * @param $string
     * @return string
     */

    public function delAccents($string){
        $string = strtolower(utf8_encode($string));
        $string = htmlentities($string, ENT_NOQUOTES, 'utf-8');
        $string = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $string);

        // Remplacer les ligatures tel que : Œ, Æ ...
        // Exemple "Å“" => "oe"
        $string = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $string);
        // Supprimer tout le reste
        $string = preg_replace('#&[^;]+;#', '', $string);
        return $string;
    }

    /**
     * Récupération de la latitude et longitude en fonction de l'adresse transmise en paramètre
     * @param $address
     * @return bool
     */
    public function getGeoInfoFromAddress($address){
        // Récupération des informations de l'adresse
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($this->delAccents($address)).'&key='.$this->googleAPIKey;
        $addressInfoJson = file_get_contents($url);

        $geoInfo = json_decode($addressInfoJson,true);

        $this->latitude = $geoInfo['results'][0]['geometry']['location']['lat'];
        $this->longitude = $geoInfo['results'][0]['geometry']['location']['lng'];

        return true;
    }

    /**
     * Génération de l'image streetView
     * @param $address
     * @return bool
     */
    public function addressStreetviewImage($address)
    {
        $url = 'https://maps.googleapis.com/maps/api/streetview?size='.$this->width.'x'.$this->height.'&key='.$this->googleAPIKey.'&fov='.$this->fov.'&heading='.$this->heading.'&pitch=0&location='.urlencode($this->delAccents($address));
        $image = file_get_contents($url);

        $this->streetviewImage = $this->folder.$this->filename.'_streetview.jpg';
        $fp  = fopen($this->streetviewImage , 'w+');
        fputs($fp, $image);
        fclose($fp);
        unset($image);

        return true;

    }

    /**
     * Génération des 4 images NORD SUD EST OUEST
     * @param $address
     * @return bool
     */
    public function addressStreetviewImage360($address)
    {
        $tabHeading = array(0,90,180,270);
        $i =0;
        foreach($tabHeading as $heading)
        {
            $url = 'https://maps.googleapis.com/maps/api/streetview?size=' . $this->width . 'x' . $this->height . '&key=' . $this->googleAPIKey . '&fov=' . $this->fov . '&heading=' . $heading . '&pitch=0&location=' . urlencode($this->delAccents($address));
            $image = file_get_contents($url);

            $this->imageNSEW[$i] = $this->folder . $this->filename . '_'.$heading.'_streetview.jpg';
            $fp = fopen($this->imageNSEW[$i], 'w+');
            fputs($fp, $image);
            fclose($fp);
            unset($image);
            $i++;
        }

        return true;
    }

    public function checkMapType($maptype){

        if(!in_array($maptype,$this->maptypeOptions)){
            $this->maptype = 'roadmap';
        }
        return true;
    }


    /**
     * Génération de la map en fonction de l'adresse
     * @param $address
     * @return bool
     */
    public function addressMapImage($address)
    {
        $this->checkMapType($this->maptype);
        $url = 'http://maps.googleapis.com/maps/api/staticmap?center='.urlencode($this->delAccents($address)).'&key='.$this->googleAPIKey.'&maptype='.$this->maptype.'&zoom='.$this->zoom.'&size='.$this->width.'x'.$this->height.'&markers=color:red|label:none|'.$this->latitude.','.$this->longitude.'';

        $image = file_get_contents($url);

        $this->mapImage = $this->folder.$this->filename;
        $fp  = fopen($this->mapImage.'_'.$this->maptype.'_map.jpg', 'w+');
        fputs($fp, $image);
        fclose($fp);
        unset($image);

        return true;
    }

    /**
     *
     */
    public function getMapImagePath($maptype = NULL){
        if($maptype == NULL){
            $img = $this->mapImage.'_roadmap_map.jpg';
        }else{
            $img = $this->mapImage.'_'.$maptype.'_map.jpg';
        }
        return $img;
    }

    /**
     * Affichage de la map en HTML
     * @return string
     */
    public function displayMap($maptype = NULL){
        if($maptype == NULL){
            $img = '<img src="'.$this->mapImage.'_roadmap_map.jpg'.'"/>';
        }else{
            $img = '<img src="'.$this->mapImage.'_'.$maptype.'_map.jpg'.'"/>';
        }
        return $img;
    }

    public function getStreetviewImagePath(){
        return $this->streetviewImage;
    }
    /**
     * Affichage de l'image streetview
     * @return string
     */
    public function displayStreetview(){
        return '<img src="'.$this->streetviewImage.'"/>';
    }

    /**
     * @return array
     */
    public function getStreetview360Images(){

        $tabimg = array();
        $i = 0;
        foreach($this->imageNSEW as $heading)
        {
            $tabimg[$i] = $heading;
            $i++;
        }

        return $tabimg;
    }


    /**
     * Affichage des 4 images à 360
     * @return string
     */
    public function displayStreetview360(){

        $img = '';
        foreach($this->imageNSEW as $heading)
        {
            $img .= '<img src="'.$heading.'"/>';
        }

        return $img;
    }

    /**
     * @return string
     */
    public function getImgFolder()
    {
        return $this->imgFolder;
    }

    /**
     * @param string $imgFolder
     */
    public function setImgFolder($imgFolder)
    {
        $this->imgFolder = $imgFolder;
    }

    /**
     * @return address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param address $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getGoogleAPIKey()
    {
        return $this->googleAPIKey;
    }

    /**
     * @param string $googleAPIKey
     */
    public function setGoogleAPIKey($googleAPIKey)
    {
        $this->googleAPIKey = $googleAPIKey;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param string $folder
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

    /**
     * @return int
     */
    public function getZoom()
    {
        return $this->zoom;
    }

    /**
     * @param int $zoom
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;
    }

    /**
     * @return int
     */
    public function getFov()
    {
        return $this->fov;
    }

    /**
     * @param int $fov
     */
    public function setFov($fov)
    {
        $this->fov = $fov;
    }

    /**
     * @return string
     */
    public function getMaptype()
    {
        return $this->maptype;
    }

    /**
     * @param string $maptype
     */
    public function setMaptype($maptype)
    {
        $this->maptype = $maptype;
    }

    /**
     * @return int
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * @param int $heading
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;
    }




}