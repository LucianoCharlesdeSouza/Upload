# Upload
Classe básica responsável por fazer upload de imagem

# Métodos públicos

<strong> <p>setType($type)</strong><br />
Responsável por receber as extensões permitidas
</p>

<strong><p>setWidth($width)</strong><br />
Responsável por receber a largura permitida para a imagem
</p>

<strong><p>setHeight($height)</strong><br/>
Responsável por receber a altura permitida para a imagem
</p>

<strong><p>setSize($size)</strong><br/>
Responsável por receber o tamanho "PESO" permitido para a imagem
</p>

<strong><p>setPath($path)</strong><br/>
Responsável por receber o caminho para onde será feito o upload
</p>

<strong><p>setFile($name_file)</strong><br/>
Responsável por receber o name do input do type file e fazer algumas validações
</p>

<strong><p>moveImage()</strong><br/>
Responsável por fazer o upload
</p>

<strong><p>getErros()</strong><br/>
Responsável retornar erros caso haja
</p>

<strong><p>getNewNameImage()</strong><br />
Recupera o novo nome da imagem
</p>

# Exemplo de Uso HTML
    <form enctype="multipart/form-data" method="post" action="">
        <input type="file" name="imagem">
        <input type="submit" value="Fazer Upload">
    </form>

# Exemplo de Uso PHP
        use App\Library\Upload;
        $upload = new Upload();
        
        if (isset($_FILES['imagem']) && !empty($_FILES['imagem'])) {
            $upload->setType('jpg');
            $upload->setType('png');

            $upload->setFile('imagem');
            $upload->setPath("App/assets/");
            if ($upload->moveImage()) {
                echo "Imagem carregada com sucesso!";
                echo "<br />";
                echo $upload->getNewNameImage();
            } else {
                echo $upload->getErros();
            }
        }


