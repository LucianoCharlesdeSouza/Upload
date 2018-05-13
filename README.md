# Upload
Classe básica responsável por fazer upload de imagem

# Métodos públicos

<strong> <p>set()</strong><br />
Responsável por receber as extensões permitidas
</p>

<strong><p>Width($width)</strong><br />
Responsável por receber a largura permitida para a imagem
</p>

<strong><p>Height($height)</strong><br/>
Responsável por receber a altura permitida para a imagem
</p>

<strong><p>Size($size)</strong><br/>
Responsável por receber o tamanho "PESO" permitido para a imagem
</p>

<strong><p>Path($path)</strong><br/>
Responsável por receber o caminho para onde será feito o upload
</p>

<strong><p>moveFile('imagem')</strong><br/>
Responsável por fazer o upload de uma única imagem/arquivo
</p>

<strong><p>moveMultipleFiles('iamgem')</strong><br/>
Responsável por fazer o upload de varias imagens/arquivos
</p>

<strong><p>getErros()</strong><br/>
Responsável retornar erros caso haja
</p>

<strong><p>getNameFile()</strong><br />
Recupera o novo nome da imagem/arquivo
</p>

<strong><p>getNameMultipleFiles($key = null)</strong><br />
Recupera um array com os nomes das imagens/arquivos<br/>
podendo receber o indice numérico do array
</p>


# Exemplo de Uso Upload Simples HTML
    <form enctype="multipart/form-data" method="post" action="">
        <input type="file" name="imagem">
        <input type="submit" value="Fazer Upload">
    </form>

# Exemplo de Uso PHP
        use App\Library\Upload;

        try {
            $upload = new Upload();

            if (isset($_FILES['imagem']) && !empty($_FILES['imagem'])) {

                $upload->set()
                        ->jpeg()
                        ->jpg()
                        ->png()
                        ->bmp()
                        ->icon()
                        ->gif()
                        ->svg()
                        ->css()
                        ->doc()
                        ->rtf()
                        ->html()
                        ->excel()
                        ->pdf()
                        ->xml()
                        ->rar()
                        ->zip()
                        ->mp3()
                        ->mp4()
                        ->wav()
                        ->Path("uploads/")
                        ->moveFile('imagem');

                if (!$upload->getErros()) {
                    var_dump($upload->getNameFile());
                } else {
                    echo $upload->getErros();
                }
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }



# Exemplo de Uso Upload Simples HTML
    <form enctype="multipart/form-data" method="post" action="">
        <input type="file" name="imagem">
        <input type="submit" value="Fazer Upload">
    </form>

# Exemplo de Uso PHP
        use App\Library\Upload;

        try {
            $upload = new Upload();

            if (isset($_FILES['imagem']) && !empty($_FILES['imagem'])) {

                $upload->set()
                        ->jpeg()
                        ->jpg()
                        ->png()
                        ->bmp()
                        ->icon()
                        ->gif()
                        ->svg()
                        ->css()
                        ->doc()
                        ->rtf()
                        ->html()
                        ->excel()
                        ->pdf()
                        ->xml()
                        ->rar()
                        ->zip()
                        ->mp3()
                        ->mp4()
                        ->wav()
                        ->Path("uploads/")
                        ->moveMultipleFiles('imagem');

                if (!$upload->getErros()) {
                    var_dump($upload->getNameMultipleFiles());
                } else {
                    echo $upload->getErros();
                }
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
