<?php
/*
*
* Classe Responsável por fazer upload de imagens
* @author Luciano Charles de Souza
*
*/
namespace App\Library;

class Upload {

    //ATRIBUTOS PRIVADOS
    private $file; //nome do input file
    private $new_name_file; //novo nome da imagem gerada
    private $permitidos = ["image/jpeg"];
    private $height = 300; //280px
    private $width = 230; //180px
    private $size = 150000; //1.5MB;
    private $path; //caminho onde será salvo a imagem
    private $error;

    //SETA OS TIPOS DE EXTENSÕES PERMITIDOS PARA A IMAGEM
    public function setType($type) {
        array_push($this->permitidos, "image/{$type}");
    }

    //SETA A LARGURA PERMITIDA DA IMAGEM
    public function setWidth($width) {
        $this->width = $width;
    }

    //SETA O TAMANHO "PESO" PERMITIDO DA IMAGEM
    public function setSize($size) {
        $this->size = $size;
    }

    //SETA A ALTURA DA PERMITIDA DA IMAGEM
    public function setHeight($height) {
        $this->height = $height;
    }

    //SETA O CAMINHO ONDE SERÁ SALVO A IMAGEM
    public function setPath($path) {
        $this->mkdir_r($path);
        $this->path = $path . $this->new_name_file;
    }
    
    //RECUPERA O NOVO NOME DA IMAGEM MAIS SUA EXTENSÃO
    public function getNewNameImage() {
        return $this->new_name_file;
    }

    //VERIFICANDO A EXISTENCIA DO ARQUIVO E FAZ AS VALIDAÇOES
    public function setFile($name_file) {

        $this->file = $_FILES[$name_file];

        if (!isset($this->file['name']) || empty($this->file['name']) || empty($this->file['tmp_name'])) {
            $this->error = "Escolha a imagem para Upload";
            return false;
        }
        $this->validMimeAndType();
        $this->validDimensions();
        $this->validSize();
        $this->setNewName();
    }

    //FAZ O UPLOAD CASO NÃO HAJA ERROS
    public function moveImage() {
        if ($this->Error()) {
            if (!move_uploaded_file($this->file['tmp_name'], $this->path)) {
                $this->error = "Não foi possivel fazer upload da imagem! Contate o administrador.";
            }
            return true;
        }
    }

    //RECUPERA OS ERROS
    public function getErros() {
        return $this->error;
    }

    //VALIDANDO O TIPO E A EXTENSÂO
    private function validMimeAndType() {
        if (!in_array($this->file['type'], $this->permitidos)) {
            $this->error = "Só pode ser enviado Arquivos do Tipo imagem e  extensões Tipo({$this->getType()}).";
        }
    }

    //VALIDANDO AS DIMENSÕES DO ARQUIVO
    private function validDimensions() {
        $dimensions = getimagesize($this->file['tmp_name']);
        if ($dimensions[0] > $this->width || $dimensions[1] > $this->height) {
            $this->error = "Esta imagem precisa está nessas dimensões {$this->width}x{$this->height}.";
        }
    }

    //VALIDANDO O TAMANHO DO ARQUIVO
    private function validSize() {
        if ($this->file['size'] > $this->size) {
            $this->error = "Esta imagem precisa ser menor que " . $this->size / 100000 . "MB";
        }
    }

    //GERA UM NOVO NOME PARA A IMAGEM
    private function setNewName() {
        $ext = pathinfo($this->file['name']);
        $this->new_name_file = hash("sha256", uniqid(rand(), true)) . '.' . $ext['extension'];
    }

    //CRIA O DIRETORIO CASO NÃO EXISTA
    private function mkdir_r($dirName, $rights = 0777) {
        $dirs = explode('/', $dirName);
        $dir = '';
        foreach ($dirs as $part) {
            $dir .= $part . '/';
            if (!is_dir($dir) && strlen($dir) > 0 && !file_exists($dir))
                mkdir($dir, $rights);
        }
    }

    //VERIFICA SE HÁ ERROS
    private function Error() {
        if (count($this->error) == 0) {
            return true;
        }
    }

    //RECUPERA OS TIPOS DE IMAGEM E SUAS EXTENSÕES PERMITIDAS
    private function getType() {
        $types = '';
        for ($i = 0; $i < count($this->permitidos); $i++) {
            $types .= $this->permitidos[$i];
        }
        return str_replace("image/", "/", $types);
    }

}
