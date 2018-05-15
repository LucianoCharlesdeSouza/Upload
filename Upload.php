<?php

/*
 *
 * Classe Responsável por fazer upload (simples e múltiplos) de imagens/arquivos
 * @author Luciano Charles de Souza
 *
 * atualizada em: 13/05/2018
 */

class Upload {
    /*
     * ATRIBUTOS
     */

    private $image_crop;
    private $crop = false;
    private $quality = '100';
    private $file; //input file
    private $new_name_file; //novo nome da imagem gerada
    private $images_name = []; //array com os nomes das imagens upadas com multiplos uploads
    private $allowed = [];
    private $height = 800; //800px
    private $width = 1546; //1546px
    private $size = 250000; //1.5MB;
    private $path; //caminho onde será salvo a imagem
    private $error;

    public function set() {
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO JPEG NAS PERMISSÕES DE UPLOAD
     */

    public function jpeg() {
        array_push($this->allowed, 'image/jpeg');
        array_push($this->allowed, 'image/jpg');
        array_push($this->allowed, 'image/pjpeg');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO PNG NAS PERMISSÕES DE UPLOAD
     */

    public function png() {
        array_push($this->allowed, 'image/png');
        array_push($this->allowed, 'image/x-png');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO GIF NAS PERMISSÕES DE UPLOAD
     */

    public function gif() {
        array_push($this->allowed, 'image/gif');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO BMP NAS PERMISSÕES DE UPLOAD
     */

    public function bmp() {
        array_push($this->allowed, 'image/bmp');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO SVG NAS PERMISSÕES DE UPLOAD
     */

    public function svg() {
        array_push($this->allowed, 'image/svg+xml');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO ICON NAS PERMISSÕES DE UPLOAD
     */

    public function icon() {
        array_push($this->allowed, 'image/x-icon');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO PDF NAS PERMISSÕES DE UPLOAD
     */

    public function pdf() {
        array_push($this->allowed, 'application/pdf');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO XML NAS PERMISSÕES DE UPLOAD
     */

    public function xml() {
        array_push($this->allowed, 'application/xml');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO XLS/XSLX NAS PERMISSÕES DE UPLOAD
     */

    public function excel() {
        array_push($this->allowed, 'application/vnd.ms-excel');
        array_push($this->allowed, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO DOC/DOCX NAS PERMISSÕES DE UPLOAD
     */

    public function doc() {
        array_push($this->allowed, 'application/msword');
        array_push($this->allowed, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO ZIP NAS PERMISSÕES DE UPLOAD
     */

    public function zip() {
        array_push($this->allowed, 'application/octet-stream');
        array_push($this->allowed, 'application/zip');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO RAR NAS PERMISSÕES DE UPLOAD
     */

    public function rar() {
        array_push($this->allowed, 'application/octet-stream');
        array_push($this->allowed, 'application/x-rar-compressed');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO HTML NAS PERMISSÕES DE UPLOAD
     */

    public function html() {
        array_push($this->allowed, 'text/html');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO MP4 NAS PERMISSÕES DE UPLOAD
     */

    public function mp4() {
        array_push($this->allowed, 'video/mp4');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO MP3 NAS PERMISSÕES DE UPLOAD
     */

    public function mp3() {
        array_push($this->allowed, 'audio/mpeg');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO WAV NAS PERMISSÕES DE UPLOAD
     */

    public function wav() {
        array_push($this->allowed, 'audio/x-wav');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO RTF NAS PERMISSÕES DE UPLOAD
     */

    public function rtf() {
        array_push($this->allowed, 'application/rtf');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO CSS NAS PERMISSÕES DE UPLOAD
     */

    public function css() {
        array_push($this->allowed, 'text/css');
        return $this;
    }

    /*
     * SETA A LARGURA PERMITIDA DA IMAGEM
     */

    public function width($width) {
        $this->width = $width;
        return $this;
    }

    /*
     * SETA O TAMANHO "PESO" PERMITIDO DA IMAGEM
     */

    public function size($size) {
        $this->size = $size;
        return $this;
    }

    /*
     * SETA A ALTURA PERMITIDA DA IMAGEM
     */

    public function height($height) {
        $this->height = $height;
        return $this;
    }

    /*
     * SETA O CAMINHO ONDE SERÁ SALVO A IMAGEM
     */

    public function path($path) {
        $this->mkdir_r($path);
        $this->path = $path;
        return $this;
    }

    /*
     * RECUPERA O NOVO NOME DA IMAGEM MAIS SUA EXTENSÃO
     */

    public function getNameFile() {
        return $this->new_name_file;
    }

    /*
     * VERIFICA A EXISTENCIA DO ARQUIVO E SE NÃO ESTA VAZIO
     */

    private function is_empty($file) {
        if ($file != null) {
            if (in_array('', $this->file['tmp_name'])) {
                $this->error = "Escolha o(s) arquivo(s) " . implode(' - ', $this->allowed) . " para upload";
                return false;
            }
        } else {

            if (!isset($this->file['name']) || empty($this->file['name']) || empty($this->file['tmp_name'])) {
                $this->error = "Escolha o arquivo " . implode(' - ', $this->allowed) . " para upload";
                return false;
            }
        }
    }

    /*
     * VERIFICA A EXISTENCIA DO ARQUIVO E FAZ AS VALIDAÇOES
     */

    private function setFile($name_file, $file = null) {
        $this->file = $_FILES[$name_file];

        if ($file != null) {
            if (in_array('', $this->file['tmp_name'])) {
                $this->error = "Escolha o(s) arquivo(s) " . implode(' - ', $this->allowed) . " para upload";
                return false;
            }
        } else {

            if (!isset($this->file['name']) || empty($this->file['name']) || empty($this->file['tmp_name'])) {
                $this->error = "Escolha o arquivo " . implode(' - ', $this->allowed) . " para upload";
                return false;
            }
        }

        $this->validMimeAndType($file);
        $this->validDimensions($file);
        $this->validSize($file);
        $this->setNewName($file);
    }

    /*
     * MÉTODO QUE ATIVA O RESIZE DA IMAGEM
     */

    public function crop($quality = null) {
        $this->crop = true;
        $this->quality = ($quality != null) ? $quality : $this->quality;
        return $this;
    }

    /*
     * MÉTODO VERIFICADOR DE PERMISSÃO PARA CROP
     */

    private function croping($name_file, $file = null) {
        $this->file = $_FILES[$name_file];

        $this->is_empty($file);

        $this->validDimensions($file);
        $this->validSize($file);
        $this->setNewName($file);

        $this->Uploadimage_crop();
    }

    private function cropingMultiple($file = null) {

        $this->validDimensions($file);
        $this->validSize($file);
        $this->setNewName($file);

        $this->Uploadimage_crop();
        $this->images_name[] = $this->getNameFile();
    }

    /*
     * FAZ O UPLOAD CASO NÃO HAJA ERROS
     * E FAZ OU NÃO O CROP DA IMAGEM
     */

    public function moveFile($file) {
        if ($this->crop) {
            $this->croping($file);
        } else {
            $this->setFile($file);
            $this->path = $this->path . $this->new_name_file;
            if ($this->Error()) {
                if (!move_uploaded_file($this->file['tmp_name'], $this->path)) {
                    $this->error = "Não foi possivel fazer upload do arquivo " . implode(' - ', $this->allowed) . "! Contate o administrador.";
                }
                return true;
            }
        }
    }

    /*
     * FAZ O UPLOAD MULTIPLO CASO NÃO HAJA ERROS
     */

    private function uploadmultiple() {
        if ($this->Error()) {
            if (!move_uploaded_file($this->file['tmp_name'], $this->path . $this->new_name_file)) {
                $this->error = "Não foi possivel fazer upload do arquivo " . implode(' - ', $this->allowed) . "! Contate o administrador.";
            }
            return true;
        }
    }

    /*
     * FAZ O UPLOAD CASO NÃO HAJA ERROS
     */

    public function moveMultipleFiles($name) {

        foreach ($this->ArrayFiles($_FILES[$name]) as $val) {

            if (!$this->crop) {
                $this->setFile($name, $val);

                $this->file = $val;

                if ($this->uploadmultiple()) {
                    $this->images_name[] = $this->getNameFile();
                }
            } else {
                $this->file = $val;

                $this->cropingMultiple($val);
            }
        }
    }

    /*
     * RECUPERA O NOME GERADO DAS IMAGENS EM FORMA DE ARRAY
     */

    public function getNameMultipleFiles($key = null) {

        if ($key != null && array_key_exists($key, $this->images_name)) {
            return $this->images_name[$key];
        }
        return $this->images_name;
    }

    /*
     * VERIFICA SE O ARRAY É MULTIPLO
     */

    private function arrayFiles($file) {
        $file_array = array();
        $file_count = count($file['name']);
        $file_key = array_keys($file);

        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_key as $val) {
                $file_array[$i][$val] = $file[$val][$i];
            }
        }
        return $file_array;
    }

    /*
     * RECUPERA OS ERROS
     */

    public function getErros() {
        return $this->error;
    }

    /*
     * VALIDA O TIPO E A EXTENSÂO
     */

    private function validMimeAndType($file = null) {

        if ($file != null) {
            if (!in_array($file['type'], $this->allowed)) {
                $this->error = "Só pode ser enviado Arquivos do Tipo ( <strong>" . implode(' - ', $this->allowed) . " ).";
                return false;
            }
        } else {
            if (!in_array($this->file['type'], $this->allowed)) {
                $this->error = "Só pode ser enviado Arquivos do Tipo ( " . implode(' - ', $this->allowed) . " ).";
                return false;
            }
        }
    }

    /*
     * VALIDA AS DIMENSÕES
     */

    private function validDimensions($file = null) {

        if ($file != null) {
            $dimensions = ($file['tmp_name'] != "") ? getimagesize($file['tmp_name']) : null;

            if ($dimensions[0] > $this->width || $dimensions[1] > $this->height && $dimensions != null) {
                $this->error = "Arquivo " . implode(' - ', $this->allowed) . " precisa estar nessas dimensões {$this->width}x{$this->height}.";
                return false;
            }
        } else {
            $dimensions = getimagesize($this->file['tmp_name']);
            if ($dimensions[0] > $this->width || $dimensions[1] > $this->height) {
                $this->error = "Arquivo " . implode(' - ', $this->allowed) . " precisa estar nessas dimensões {$this->width}x{$this->height}.";
                return false;
            }
        }
    }

    /*
     * VALIDA O TAMANHO
     */

    private function validSize($file = null) {
        if ($file != null) {
            if ($file['size'] > $this->size) {
                $this->error = "Arquivo " . implode(' - ', $this->allowed) . " precisa ser menor que " . $this->size / 100000 . "MB";
                return false;
            }
        } else {
            if ($this->file['size'] > $this->size) {
                $this->error = "Arquivo " . implode(' - ', $this->allowed) . " precisa ser menor que " . $this->size / 100000 . "MB";
                return false;
            }
        }
    }

    /*
     * GERA UM NOVO NOME PARA IMAGEM/ARQUIVO
     */

    private function setNewName($file = null) {
        if ($file != null) {
            $ext = (array_key_exists('extension', pathinfo($file['name']))) ? pathinfo($file['name']) : null;
            $this->new_name_file = hash("sha256", uniqid(rand(), true)) . '.' . $ext['extension'];
        } else {
            $ext = pathinfo($this->file['name']);
            $this->new_name_file = hash("sha256", uniqid(rand(), true)) . '.' . $ext['extension'];
        }
    }

    /*
     * CRIA O DIRETORIO CASO NÃO EXISTA
     */

    private function mkdir_r($dirName, $rights = 0777) {
        $dirs = explode('/', $dirName);
        $dir = '';
        foreach ($dirs as $part) {
            $dir .= $part . '/';
            if (!is_dir($dir) && strlen($dir) > 0 && !file_exists($dir))
                mkdir($dir, $rights);
        }
    }

    /* /
     * RETORNA OS ERROS CASO HAJA
     */

    private function error() {
        if (count($this->error) == 0) {
            return true;
        }
    }

    /*
     * RECUPERA OS TIPOS DE IMAGEM E SUAS EXTENSÕES PERMITIDAS
     */

    private function getType() {
        $types = '';
        for ($i = 0; $i < count($this->allowed); $i++) {
            $types .= $this->allowed[$i] . ' - ';
        }
        return rtrim($types, ' - ');
    }

    /*
     * REALIZA O CROP DE IMAGENS
     */

    private function Uploadimage_crop() {

        $rotation = null;
        switch ($this->file['type']):
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $rotation = (function_exists('exif_read_data') && exif_imagetype($this->file['tmp_name']) == 2 ? @exif_read_data($this->file['tmp_name']) : null);
                $this->image_crop = imagecreatefromjpeg($this->file['tmp_name']);
                break;
            case 'image/png':
            case 'image/x-png':
                $rotation = (function_exists('exif_read_data') && exif_imagetype($this->file['tmp_name']) == 3 ? @exif_read_data($this->file['tmp_name']) : null);
                $this->image_crop = imagecreatefrompng($this->file['tmp_name']);
                break;
            case 'image/gif':
                $rotation = (function_exists('exif_read_data') && exif_imagetype($this->file['tmp_name']) == 1 ? @exif_read_data($this->file['tmp_name']) : null);
                $this->image_crop = imagecreatefromgif($this->file['tmp_name']);
                break;
            default :
                $this->image_crop = null;
                break;
        endswitch;

        /*
         * Rotacionar a imagem caso necessitar
         */
        if (!empty($rotation['Orientation'])) {
            switch ($rotation['Orientation']) {
                case 3:
                    $this->image_crop = imagerotate($this->image_crop, 180, 0);
                    break;
                case 6:
                    $this->image_crop = imagerotate($this->image_crop, -90, 0);
                    break;
                case 8:
                    $this->image_crop = imagerotate($this->image_crop, 90, 0);
                    break;
                default :
                    $this->image_crop = $this->image_crop;
                    break;
            }
        }


        if (!$this->image_crop):
            $this->error = 'Campo vazio ou Tipo de arquivo inválido, envie imagens JPG/PNG/GIF!';
            return false;
        else:
            $x = imagesx($this->image_crop);
            $y = imagesy($this->image_crop);
            $image_cropX = ( $this->width < $x ? $this->width : $x );
            $image_cropH = ($image_cropX * $y) / $x;

            $Newimage_crop = imagecreatetruecolor($image_cropX, $image_cropH);
            imagealphablending($Newimage_crop, false);
            imagesavealpha($Newimage_crop, true);
            imagecopyresampled($Newimage_crop, $this->image_crop, 0, 0, 0, 0, $image_cropX, $image_cropH, $x, $y);

            switch ($this->file['type']):
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($Newimage_crop, $this->path . $this->new_name_file, $this->quality);
                    break;
                case 'image/png':
                case 'image/x-png':
                    imagepng($Newimage_crop, $this->path . $this->new_name_file);
                    break;
                case 'image/gif':
                    imagegif($Newimage_crop, $this->path . $this->new_name_file);
                    break;
            endswitch;

            if (!$Newimage_crop):
                $this->error = 'Campo vazio ou Tipo de arquivo inválido, envie imagens JPG/PNG/GIF!';
                return false;
            else:
                $this->error = null;
            endif;

            imagedestroy($this->image_crop);
            imagedestroy($Newimage_crop);
        endif;
    }

}
