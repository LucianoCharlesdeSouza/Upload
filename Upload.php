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

    private $file; //input file
    private $new_name_file; //novo nome da imagem gerada
    private $images_name = []; //array com os nomes das imagens upadas com multiplos uploads
    private $allowed = [];
    private $type; //tipo de arquivo ou imagem
    private $height = 800; //800px
    private $width = 1546; //1546px
    private $size = 150000; //1.5MB;
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
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO JPG NAS PERMISSÕES DE UPLOAD
     */

    public function jpg() {
        array_push($this->allowed, 'image/jpg');
        return $this;
    }

    /*
     * ADICIONA A EXTENSÃO PNG NAS PERMISSÕES DE UPLOAD
     */

    public function png() {
        array_push($this->allowed, 'image/png');
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

    public function Width($width) {
        $this->width = $width;
        return $this;
    }

    /*
     * SETA O TAMANHO "PESO" PERMITIDO DA IMAGEM
     */

    public function Size($size) {
        $this->size = $size;
        return $this;
    }

    /*
     * SETA A ALTURA PERMITIDA DA IMAGEM
     */

    public function Height($height) {
        $this->height = $height;
        return $this;
    }

    /*
     * SETA O CAMINHO ONDE SERÁ SALVO A IMAGEM
     */

    public function Path($path) {
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
     * VERIFICA A EXISTENCIA DO ARQUIVO E FAZ AS VALIDAÇOES
     */

    public function setFile($name_file, $file = null) {
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
     * FAZ O UPLOAD CASO NÃO HAJA ERROS
     */

    public function moveFile($file) {
        $this->setFile($file);
        $this->path = $this->path . $this->new_name_file;
        if ($this->Error()) {
            if (!move_uploaded_file($this->file['tmp_name'], $this->path)) {
                $this->error = "Não foi possivel fazer upload do arquivo " . implode(' - ', $this->allowed) . "! Contate o administrador.";
            }
            return true;
        }
    }

    /*
     * FAZ O UPLOAD CASO NÃO HAJA ERROS
     */

    private function movemultiple() {
        $this->path = $this->path . $this->new_name_file;
        if ($this->Error()) {
            if (!move_uploaded_file($this->file['tmp_name'], $this->path)) {
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

            $this->setFile($name, $val);

            $this->file = $val;

            if ($this->movemultiple()) {
                $this->images_name[] = $this->getNameFile();
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

    private function ArrayFiles($file) {
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

    private function Error() {
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

}
