# Upload
Classe responsável por fazer upload simples e múltiplos de imagens/arquivos com ou sem crop.

# Métodos públicos

<strong> <p>set()</strong><br />
Responsável por receber as extensões permitidas
</p>

<strong><p>width($width)</strong><br />
Responsável por receber a largura permitida para a imagem, tambem pode ser usada para definir o crop.
</p>

<strong><p>height($height)</strong><br/>
Responsável por receber a altura permitida para a imagem, tambem pode ser usada para definir o crop.
</p>

<strong><p>size($size)</strong><br/>
Responsável por receber o tamanho "PESO" permitido para a imagem
</p>

<strong><p>path($path)</strong><br/>
Responsável por receber o caminho para onde será feito o upload
</p>

<strong><p>crop()</strong><br />
Pode receber uma string com o valor da qualidade da imagem a ser criada<br />
crop(80)<br/>
Valor padrão é de 100 "ótima qualidade".
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
                        ->jpeg() engloba os mimes (jpeg,jpg,pjeg)
                        ->png()  engloba os mimes (png,x-png)
                        ->bmp()
                        ->icon()
                        ->gif()
                        ->svg()
                        ->css()
                        ->doc()  engloba os mimes (doc,docx)
                        ->rtf()
                        ->html()
                        ->excel()  engloba os mimes (xls,xlsx)
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



# Exemplo de Uso Upload Múltiplos HTML
    <form enctype="multipart/form-data" method="post" action="">
        <input type="file" name="imagem[]" multiple="">
        <input type="submit" value="Fazer Upload">
    </form>

# Exemplo de Uso PHP
        use App\Library\Upload;

        try {
            $upload = new Upload();

            if (isset($_FILES['imagem']) && !empty($_FILES['imagem'])) {

                $upload->set()
                        ->jpeg() engloba os mimes (jpeg,jpg,pjeg)
                        ->png()  engloba os mimes (png,x-png)
                        ->bmp()
                        ->icon()
                        ->gif()
                        ->svg()
                        ->css()
                        ->doc()  engloba os mimes (doc,docx)
                        ->rtf()
                        ->html()
                        ->excel()  engloba os mimes (xls,xlsx)
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
        
        
# Exemplo de Uso Com a Função Crop        
        
         try {
            $upload = new Upload();

            if (isset($_FILES['imagem']) && !empty($_FILES['imagem'])) {

                $upload->set()
                        ->jpeg() engloba os mimes (jpeg,jpg,pjeg)
                        ->png()  engloba os mimes (png,x-png)
                        ->gif()
                        ->path("uploads/")
                        ->width(600)
                        ->crop()
                        ->moveMultipleFiles('imagem');

                if (!$upload->getErros()) {
                    echo '<pre>';
                    var_dump($upload->getNameMultipleFiles());
                } else {
                    echo $upload->getErros();
                }
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
