/*
    Copyright (C) 2016 Apple Inc. All Rights Reserved.
    See LICENSE.txt for this sample’s licensing information
    
    Abstract:
    Shows how to generate results compatible with our code from PHP.
 */

// The PHP installed on macOS does not include mcrypt, and that seems to be the most commonly 
// used PHP AES implementation.  Rather than go to the trouble of installing mcrypt on a Mac, 
// I ran these tests in various online PHP sandboxen:
//
// o <http://sandbox.onlinephpfunctions.com/> running PHP 7.0.5 for the bulk of the tests; 
//   this sandbox does not support HMAC-SHA so you must comment out the call to 
//   `DigestTests_testHMACSHA`
//
// o <http://ideone.com/> running PHP 5.6.11-1 for the HMAC-SHA test; this sandbox supports 
//   HMAC-SHA but does not support mcrypt, so you must comment out all the tests except 
//   `DigestTests_testHMACSHA`
// 
// I didn't implement the RSA tests in PHP because PHP doesn't include its own built-in RSA 
// implementation, and it seems that a lot of folks just use OpenSSL's implementation from 
// PHP.

assert_options(ASSERT_BAIL);

function QHex_hexStringFromBytes($bytes)
{
    return bin2hex($bytes);
}

function QHex_bytesFromHexString($hexString)
{
    return hex2bin($hexString);
}

function QIO_bytesWithContentsOfFile($fileName)
{
    if ($fileName == "test.cer") {
        $result = QHex_bytesFromHexString("308202F8308201E0A003020102020101300B06092A864886F70D01010B301C310D300B06035504030C0454657374310B3009060355040613024742301E170D3133303431303139323131305A170D3233303430383139323131305A301C310D300B06035504030C0454657374310B300906035504061302474230820122300D06092A864886F70D01010105000382010F003082010A0282010100A596F46AB37AACA46A29E2BDE40AD60E88207699023B5F0C888BF77D9D2CD5ABFA56F4FE121E157AE8DD2D9213F4BFC1FF5DB0A26A975D937A3B99E86E24BF14E651DA5F63B585233DCCBF12160F43B477C66438779A2A3DE606E0699C2152DECAD7C309CF2E680090FC3444D598C4289E55A175723CEC9B9482B4E3D342E8B638E6D4A2D1366BBDEE7AF2B7AF11D4F3FBF392D4F4B65C346A025366F0C5755EE0FD118BA854521FBA6C4331BF7E538472FE26C6AC9ECA21ADDD05E10151C321B5846CB7CBF023C9D83922586AC788F0222092851031E99882525E2676535E77DFEFFEBE5CEBF26C683BB6B0FBD521A009F110CC14539EB9687704D33463CD3B0203010001A3473045300E0603551D0F0101FF04040302078030160603551D250101FF040C300A06082B06010505070304301B0603551D1104143012811074657374406578616D706C652E636F6D300D06092A864886F70D01010B050003820101002C5E0B3DDB05BFA774C12D0F3D81835C1EA5778631333C60AF7191E78B1E9FB45B6548DB8D894DF285D25C9FFB8D221CAC8B98817D213C1C73DE7879951F48665852981058C3AEA5794FBAF3BEB7581EFE503F06299B3B9F62776D89C0A3D284B66CC6539CC8D78162853BBC0D553DC1C18014E40946747895048A2F6344B638CD2F8806A9FBDCBAC47157D6296D71ADAA831FA4316CFA16ADC43D180F1014FA568F070E8CA121932DB1DA2AADD1C4B29E4F04781CFF520DD864BE8D72CFAFE95DD68CB3025ABD169EBB6E0DACCA595E275E494E676B268FB54B9D647FD58F645F21943C24A7F470FF4EA3B350584AC22AD073E5046866213E21062EA4D6E096");
    } else if ($fileName == "test.pem") {
        $result = QHex_bytesFromHexString("4D4949432B4443434165436741774942416749424154414C42676B71686B694739773042415173774844454E4D41734741315545417777455647567A6444454C0A4D416B474131554542684D43523049774868634E4D544D774E4445774D546B794D5445775768634E4D6A4D774E4441344D546B794D544577576A41634D5130770A4377594456515144444152555A584E304D517377435159445651514745774A48516A4343415349774451594A4B6F5A496876634E4151454242514144676745500A4144434341516F4367674542414B57573947717A6571796B61696E697665514B316736494948615A416A74664449694C393332644C4E57722B6C62302F6849650A4658726F33533253452F532F77663964734B4A716C313254656A755A3647346B7678546D5564706659375746497A334D7678495744304F3064385A6B4F4865610A4B6A336D427542706E4346533373725877776E504C6D67416B507730524E57597843696556614631636A7A736D355343744F5054517569324F4F62556F7445320A6137337565764B3372784855382F767A6B745430746C773061674A545A764446645637672F52474C7146525348377073517A472F666C4F456376346D787179650A79694774335158684156484449625745624C664C3843504A32446B695747724869504169494A4B46454448706D494A5358695A3255313533332B2F2B766C7A720A386D786F4F3761772B3955686F416E78454D77555535363561486345307A526A7A5473434177454141614E484D45557744675944565230504151482F424151440A416765414D425947413155644A5145422F77514D4D416F47434373474151554642774D454D42734741315564455151554D424B424548526C633352415A5868680A625842735A53356A623230774451594A4B6F5A496876634E4151454C425141446767454241437865437A336242622B6E644D4574447A324267317765705865470A4D544D38594B39786B65654C48702B30573256493234324A54664B46306C79662B343069484B794C6D4946394954776363393534655A556653475A59557067510A574D4F7570586C5075764F2B743167652F6C412F42696D624F3539696432324A774B5053684C5A73786C4F63794E6542596F553776413156506348426742546B0A43555A30654A55456969396A524C59347A532B4942716E37334C7245635666574B573178726171444836517862506F577263513947413851465070576A77634F0A6A4B45686B79327832697174306353796E6B384565427A2F556733595A4C364E63732B76365633576A4C4D43577230576E72747544617A4B5756346E586B6C4F0A5A32736D6A37564C6E57522F3159396B587947555043536E3948442F54714F7A5546684B77697251632B554561475968506945474C715457344A593D0A");
    } else if ($fileName == "plaintext-332.dat") {
        $result = QHex_bytesFromHexString("546865204170706C6520536F6674776172652069732070726F7669646564206279204170706C65206F6E20616E20224153204953222062617369732E204150504C45204D414B4553204E4F2057415252414E544945532C2045585052455353204F5220494D504C4945442C20494E434C5544494E4720574954484F5554204C494D49544154494F4E2054484520494D504C4945442057415252414E54494553204F46204E4F4E2D494E4652494E47454D454E542C204D45524348414E544142494C49545920414E44204649544E45535320464F52204120504152544943554C415220505552504F53452C20524547415244494E4720544845204150504C4520534F465457415245204F52204954532055534520414E44204F5045524154494F4E20414C4F4E45204F5220494E20434F4D42494E4154494F4E205749544820594F55522050524F44554354532E");
    } else if ($fileName == "plaintext-336.dat") {
        $result = QHex_bytesFromHexString("546865204170706C6520536F6674776172652069732070726F7669646564206279204170706C65206F6E20616E20224153204953222062617369732E204150504C45204D414B4553204E4F2057415252414E544945532C2045585052455353204F5220494D504C4945442C20494E434C5544494E4720574954484F5554204C494D49544154494F4E2054484520494D504C4945442057415252414E54494553204F46204E4F4E2D494E4652494E47454D454E542C204D45524348414E544142494C49545920414E44204649544E45535320464F52204120504152544943554C415220505552504F53452C20524547415244494E4720544845204150504C4520534F465457415245204F52204954532055534520414E44204F5045524154494F4E20414C4F4E45204F5220494E20434F4D42494E4154494F4E205749544820594F55522050524F44554354532E5F5F5F5F");
    } else if ($fileName == "cyphertext-aes-128-ecb-336.dat") {
        $result = QHex_bytesFromHexString("E531BEF11ABF63457761B282382F33872267B3E339A37D6EB0256350FE750BE958F65891AF3D76A02C2BCCF9724227C5A569EA418599563F675A6225F420E707975E6B3AD359B2E306C311F0B5066B90DE552C28B4054FB7643B92A119D42A53D7623A314AB8D8B822C71EAA9825776E6412075A7CAA0507FDBF6091A7AE4297326D003E49AD0670B7D1E68E50D042ED60F565D5618643A9983DB4C10AC92B963A4EF8A18B2F7B7210696089590A529C0A221310EA4F1BB1F5DBC9944802D969E33FB3E53462F95C83DCB2ABF0416893F6DAC49A530463E718E0F3B82941B567CB29266D1C4642B3437FC755855EA918E82ABC8DE5E847E81920A7A567B0B9C13A8C01D275DF0A7C5465BFE41733B7A031459104643833ADEB633B670C8FFEC4B8757B2FD45177F6F1572C10FA774AD5E4E7628B33230A3FD51E18FB4C15E4A3AB9C61D2AD539FDF56FE867C8A199CE4");
    } else if ($fileName == "cyphertext-aes-128-cbc-336.dat") {
        $result = QHex_bytesFromHexString("42D3825C0C76C05244DDEC0810064FF762C25ED28C44C82CD34657086A272616B7EDC62B4A69766F56DBF10DA2A2B1EF15B00AA006C673B012E758F4C4A2016297EBA166776D4D864E5DBC443B4C71299EA27A587A6E7BBFF67CD5A9980894CF2175FB87BB38A0D6E6BB82A17BDC9A838CDE8B53064D07769970CAEE93FC5E51C92FA25ABC5C099161E0907CF99961515DE687CA5223855250DCFB60B89BAADA44339E7A7C8BD1E8231E098EFB5E97860C30F53DAC057349249BCE1E569F608A0D44A85F8C193900C320141B6E4274FCDBC95A05B497084F88171915A1279A79D384C9DAF07A31EA5F4C6A45899939E4F32C786AF2BE7CD0DF94C501CAD2680847900C230CF478F0553DE32E313EEC4EAB984EF4352BA7F37D2E747A9C0CF1C7969F43F127D9F45150AEFA895406DF51C795B6E0F12FB83FD44E3F017BCD761FCB9BECB9CCF5D816171B33BA8C822DB1");
    } else if ($fileName == "cyphertext-aes-256-ecb-336.dat") {
        $result = QHex_bytesFromHexString("D0A65FF3B4D995744E9F9B1887721290C46F7607B10958CDC8A39FE2117B6A78BC45A13898604C76C2DC8BD2FFED9E3EE2F22B2F95B1360D544D40265424F6EDBA87FA67166111D72A56854FC3636F921552CD3560367BBCCD9023156B01736C6608F6AD71563FE090642C432AE8A3F01FF6FFD001950C2E2110956166B34852E3E913F6CFAD1317C2D8460F4C83A4775ECA651A7989DA7F9BE4E147C1636072874A56D9BDD9352AE1FA54E0BB3EFC3AE1E22B857DD4EDF1A034EEAFCFD7DF2907729FB4DB5C14D77101258EEF8CF130C99F54BF091F6CED562782B363613763FF825445D75894C0D4E94C5745785FF81EC24DDE917D31FCDAA91BB083CC45635B3E6AB991C95B795EDE9B4F3446061411AC9E0C7A672A0BA4BD0DD7279666410B98D73138557D10DCA3F01C8D1E1FC0A318E8F0DBF7FA46AD179449F76791908AE6A1FCD9A8C1BF3F729ACA29409D89");
    } else if ($fileName == "cyphertext-aes-256-cbc-336.dat") {
        $result = QHex_bytesFromHexString("DD7B234D6705FEBF288D45FD93786CEB2022D8248988AD1F1C9E7E616AE64E79A2471BA37CFE51EDF1EB9F5E019B4730D72F8722DFA9EB1F5CA35FEA18DE2D1808488C84BCE983A74E4892BB76A79C71278B40822EC3C592A59F87C390F28E75CBDB94C1BFC660C76ABAAA3526B0E349CA263972EB89019FC94467741FCDBA2CB19599FE1CDA4DE7326BE8CE660FD38E2517825A9C21A228F07199D84A5A588094369A7FA5FAC16EA70468F52E387301F499F8481253CF06670B07F2453AEED72B4503E7501D52D8FA73C6BE79650111810B940EA49620C960BEB4EA1FCCC4226AE652DE622561CFE12D1C0B65D3C308EE005B610672AAB62F3DC723AEF7A7C3A97EBFC7F03B68B6EB22A28E70E3D32783DB1CD2483CEE6336AA9DD72D247CD2221EFB42E44FA748B23FB6D325AD9372028F0E7057F474C92D1EEBC64A782133C7C2D143B7D55BF5835A3C8EA63C5A07");
    } else if ($fileName == "cyphertext-aes-128-cbc-332.dat") {
        $result = QHex_bytesFromHexString("42D3825C0C76C05244DDEC0810064FF762C25ED28C44C82CD34657086A272616B7EDC62B4A69766F56DBF10DA2A2B1EF15B00AA006C673B012E758F4C4A2016297EBA166776D4D864E5DBC443B4C71299EA27A587A6E7BBFF67CD5A9980894CF2175FB87BB38A0D6E6BB82A17BDC9A838CDE8B53064D07769970CAEE93FC5E51C92FA25ABC5C099161E0907CF99961515DE687CA5223855250DCFB60B89BAADA44339E7A7C8BD1E8231E098EFB5E97860C30F53DAC057349249BCE1E569F608A0D44A85F8C193900C320141B6E4274FCDBC95A05B497084F88171915A1279A79D384C9DAF07A31EA5F4C6A45899939E4F32C786AF2BE7CD0DF94C501CAD2680847900C230CF478F0553DE32E313EEC4EAB984EF4352BA7F37D2E747A9C0CF1C7969F43F127D9F45150AEFA895406DF51C795B6E0F12FB83FD44E3F017BCD761FE9ACCD87F226423B2591F654672476A0");
    } else if ($fileName == "cyphertext-aes-256-cbc-332.dat") {
        $result = QHex_bytesFromHexString("DD7B234D6705FEBF288D45FD93786CEB2022D8248988AD1F1C9E7E616AE64E79A2471BA37CFE51EDF1EB9F5E019B4730D72F8722DFA9EB1F5CA35FEA18DE2D1808488C84BCE983A74E4892BB76A79C71278B40822EC3C592A59F87C390F28E75CBDB94C1BFC660C76ABAAA3526B0E349CA263972EB89019FC94467741FCDBA2CB19599FE1CDA4DE7326BE8CE660FD38E2517825A9C21A228F07199D84A5A588094369A7FA5FAC16EA70468F52E387301F499F8481253CF06670B07F2453AEED72B4503E7501D52D8FA73C6BE79650111810B940EA49620C960BEB4EA1FCCC4226AE652DE622561CFE12D1C0B65D3C308EE005B610672AAB62F3DC723AEF7A7C3A97EBFC7F03B68B6EB22A28E70E3D32783DB1CD2483CEE6336AA9DD72D247CD2221EFB42E44FA748B23FB6D325AD9372028F0E7057F474C92D1EEBC64A7821334F2A4144D1C945135A0D644ED6F5549E");
    } else {
        assert(false);
    }
    return $result;
}

function QIO_stringWithContentsOfFile($fileName)
{
    return QIO_bytesWithContentsOfFile($fileName);
}

// Base64

function Base64Tests_testBase64Encode()
{
    $inputBytes = QIO_bytesWithContentsOfFile("test.cer");
    $expectedOutputString = QIO_stringWithContentsOfFile("test.pem");
    $expectedOutputString = str_replace("\n", "", $expectedOutputString);   // there's no way to tell base64_encode to add line breaks, so we strip them from the expected string
    $outputString = base64_encode($inputBytes);
    assert($outputString == $expectedOutputString);
}

function Base64Tests_testBase64Decode()
{
    $inputString = QIO_stringWithContentsOfFile("test.pem");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("test.cer");
    $outputBytes = base64_decode($inputString);
    assert($outputBytes == $expectedOutputBytes);
}

// key derivation

function KeyDerivationTests_testPBKDF2()
{
    $passwordString = "Hello Cruel World!";
    $saltBytes = "Some salt sir?";

    $algorithms = array( 
        "sha1",
        "sha224",
        "sha256",
        "sha384",
        "sha512"
    );
    $expected = array(
        "e56c27f5eed251db50a3", 
        "88597c3d039227ea2723", 
        "884185449fa0f5ea91bf", 
        "7c44bd93a3f5d732a667", 
        "d4537676e0af5274ca01"
    );
    $expectedNoSalt = array(
        "98b4c8aec38c64c8e2de", 
        "8bd95e3da6187c36d737", 
        "338919ba6253c606fc02", 
        "821d33494a485633ebb9", 
        "80878761083c187e425c"
    );
    $expectedDegenerate = array(
        "6e40910ac02ec89cebb9", 
        "7df7ef68f01b61a28b21", 
        "4fc58a21c100ce1835b8", 
        "9cbfe72d194da34e17c8", 
        "cb93096c3a02beeb1c5f"
    );

    for ($i = 0; $i < count($algorithms); $i++) {
        $algorithm = $algorithms[$i];
        $expectedKeyBytes = QHex_bytesFromHexString($expected[$i]);
        $keyBytes = hash_pbkdf2($algorithm, $passwordString, $saltBytes, 1000, 10, true);
        assert($keyBytes == $expectedKeyBytes);

        $expectedKeyBytes = QHex_bytesFromHexString($expectedNoSalt[$i]);
        $keyBytes = hash_pbkdf2($algorithm, $passwordString, "", 1000, 10, true);
        assert($keyBytes == $expectedKeyBytes);

        $expectedKeyBytes = QHex_bytesFromHexString($expectedDegenerate[$i]);
        $keyBytes = hash_pbkdf2($algorithm, "", "", 1000, 10, true);
        assert($keyBytes == $expectedKeyBytes);
    }
}

// digests

function DigestTests_testSHA()
{
    $inputBytes = QIO_bytesWithContentsOfFile("test.cer");
    $algorithms = array(
        "sha1",
        "sha224",
        "sha256",
        "sha384",
        "sha512"
    );
    $digestsOfTestDotCer = array(
        "c1ddfe7dd14c9b8dee83b46b87a408970fd2a83f",
        "d71908c49c7c1563a829882f1ba6115e1616d1bdbb1d1f757265137b",
        "d69cb53f849c80d7803294ee8fed312e917656986538d14224468185fac56289",
        "b1cbdc8c517ad3b0b96436839bfc9cdaf75609c4d8f908444eb31675909912ae73252e0df8a6c8599e81f2a0a760f182",
        "a1b17242359bb8dbb0cda8356991f65131ca1894ef9f797b296e68dacd300e0e179e28823cd69da1cccc8a3a8d7339bf2c1311b018c48a0e53d488e66df22250"
    );
    for ($i = 0; $i < count($algorithms); $i++) {
        $algorithm = $algorithms[$i];
        $expectedOutputBytes = QHex_bytesFromHexString($digestsOfTestDotCer[$i]);
        $outputBytes = hash($algorithm, $inputBytes, true);
        assert($outputBytes == $expectedOutputBytes);
    }
}

function DigestTests_testHMACSHA()
{
    $inputBytes = QIO_bytesWithContentsOfFile("test.cer");
    $keyBytes = QHex_bytesFromHexString("48656c6c6f20437275656c20576f726c6421");
    $algorithms = array(
        "sha1",
        "sha224",
        "sha256",
        "sha384",
        "sha512"
    );
    $hmacsOfTestDotCer = array(
        "550a1da058c1b5df6ea167870ae6dbc92f0e0281",
        "aea439459bf3b7732886d9345c7f2651de94c45ebfc320b1b49c3057",
        "5ad394b17fb3f064079b0a21f25758550f7c8d9065803ae7271cb7bb86dac081",
        "78b0fd6c8241261010ad92a9a91538aac46a90989eebdda0cb2564b2dea26061f341eb379d71af720d961c295fbbf5cc",
        "7ab5c9a876bd52ca9a9cf643ba097e6847ac02797e69f5d39fbdb4ce70390098b978faa022889496c22f0c787e41b17fe9456bb648b2c66ceb53c2dc3cc2c16e"
    );
    for ($i = 0; $i < count($algorithms); $i++) {
        $algorithm = $algorithms[$i];
        $expectedOutputBytes = QHex_bytesFromHexString($hmacsOfTestDotCer[$i]);
        $outputBytes = hash_hmac($algorithm, $inputBytes, $keyBytes, true);
        assert($outputBytes == $expectedOutputBytes);
    }
}

// AES-128

function CryptorTests_testAES128ECBEncryption()
{
    $inputBytes = QIO_bytesWithContentsOfFile("plaintext-336.dat");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("cyphertext-aes-128-ecb-336.dat");
    $keyBytes = QHex_bytesFromHexString("0C1032520302EC8537A4A82C4EF7579D");
    $outputBytes = mcrypt_encrypt(
        MCRYPT_RIJNDAEL_128,
        $keyBytes, 
        $inputBytes, 
        MCRYPT_MODE_ECB, 
        null
    );
    assert($outputBytes == $expectedOutputBytes);
}

function CryptorTests_testAES128ECBDecryption()
{
    $inputBytes = QIO_bytesWithContentsOfFile("cyphertext-aes-128-ecb-336.dat");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("plaintext-336.dat");
    $keyBytes = QHex_bytesFromHexString("0C1032520302EC8537A4A82C4EF7579D");
    $outputBytes = mcrypt_decrypt(
        MCRYPT_RIJNDAEL_128,
        $keyBytes, 
        $inputBytes, 
        MCRYPT_MODE_ECB, 
        null
    );
    assert($outputBytes == $expectedOutputBytes);
}

function CryptorTests_testAES128CBCEncryption()
{
    $inputBytes = QIO_bytesWithContentsOfFile("plaintext-336.dat");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("cyphertext-aes-128-cbc-336.dat");
    $keyBytes = QHex_bytesFromHexString("0C1032520302EC8537A4A82C4EF7579D");
    $ivBytes = QHex_bytesFromHexString("AB5BBEB426015DA7EEDCEE8BEE3DFFB7");
    $outputBytes = mcrypt_encrypt(
        MCRYPT_RIJNDAEL_128,
        $keyBytes, 
        $inputBytes, 
        MCRYPT_MODE_CBC, 
        $ivBytes
    );
    assert($outputBytes == $expectedOutputBytes);
}

function CryptorTests_testAES128CBCDecryption()
{
    $inputBytes = QIO_bytesWithContentsOfFile("cyphertext-aes-128-cbc-336.dat");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("plaintext-336.dat");
    $keyBytes = QHex_bytesFromHexString("0C1032520302EC8537A4A82C4EF7579D");
    $ivBytes = QHex_bytesFromHexString("AB5BBEB426015DA7EEDCEE8BEE3DFFB7");
    $outputBytes = mcrypt_decrypt(
        MCRYPT_RIJNDAEL_128,
        $keyBytes, 
        $inputBytes, 
        MCRYPT_MODE_CBC, 
        $ivBytes
    );
    assert($outputBytes == $expectedOutputBytes);
}

// AES-256

function CryptorTests_testAES256ECBEncryption()
{
    $inputBytes = QIO_bytesWithContentsOfFile("plaintext-336.dat");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("cyphertext-aes-256-ecb-336.dat");
    $keyBytes = QHex_bytesFromHexString("0C1032520302EC8537A4A82C4EF7579D2b88e4309655eb40707decdb143e328a");
    $outputBytes = mcrypt_encrypt(
        MCRYPT_RIJNDAEL_128,
        $keyBytes, 
        $inputBytes, 
        MCRYPT_MODE_ECB, 
        null
    );
    assert($outputBytes == $expectedOutputBytes);
}

function CryptorTests_testAES256ECBDecryption()
{
    $inputBytes = QIO_bytesWithContentsOfFile("cyphertext-aes-256-ecb-336.dat");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("plaintext-336.dat");
    $keyBytes = QHex_bytesFromHexString("0C1032520302EC8537A4A82C4EF7579D2b88e4309655eb40707decdb143e328a");
    $outputBytes = mcrypt_decrypt(
        MCRYPT_RIJNDAEL_128,
        $keyBytes, 
        $inputBytes, 
        MCRYPT_MODE_ECB, 
        null
    );
    assert($outputBytes == $expectedOutputBytes);
}

function CryptorTests_testAES256CBCEncryption()
{
    $inputBytes = QIO_bytesWithContentsOfFile("plaintext-336.dat");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("cyphertext-aes-256-cbc-336.dat");
    $keyBytes = QHex_bytesFromHexString("0C1032520302EC8537A4A82C4EF7579D2b88e4309655eb40707decdb143e328a");
    $ivBytes = QHex_bytesFromHexString("AB5BBEB426015DA7EEDCEE8BEE3DFFB7");
    $outputBytes = mcrypt_encrypt(
        MCRYPT_RIJNDAEL_128,
        $keyBytes, 
        $inputBytes, 
        MCRYPT_MODE_CBC, 
        $ivBytes
    );
    assert($outputBytes == $expectedOutputBytes);
}

function CryptorTests_testAES256CBCDecryption()
{
    $inputBytes = QIO_bytesWithContentsOfFile("cyphertext-aes-256-cbc-336.dat");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("plaintext-336.dat");
    $keyBytes = QHex_bytesFromHexString("0C1032520302EC8537A4A82C4EF7579D2b88e4309655eb40707decdb143e328a");
    $ivBytes = QHex_bytesFromHexString("AB5BBEB426015DA7EEDCEE8BEE3DFFB7");
    $outputBytes = mcrypt_decrypt(
        MCRYPT_RIJNDAEL_128,
        $keyBytes, 
        $inputBytes, 
        MCRYPT_MODE_CBC, 
        $ivBytes
    );
    assert($outputBytes == $expectedOutputBytes);
}

// AES-128 Pad CBC

function CryptorTests_testAES128PadCBCEncryption()
{
    $inputBytes = QIO_bytesWithContentsOfFile("plaintext-332.dat");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("cyphertext-aes-128-cbc-332.dat");
    $keyBytes = QHex_bytesFromHexString("0C1032520302EC8537A4A82C4EF7579D");
    $ivBytes = QHex_bytesFromHexString("AB5BBEB426015DA7EEDCEE8BEE3DFFB7");

    // PHP doesn't have built-in support for PKCS#7 padding so we add it by hand.
    
    $pad = 16 - (strlen($inputBytes) % 16);
    $inputBytes .= str_repeat(chr($pad), $pad);

    $outputBytes = mcrypt_encrypt(
        MCRYPT_RIJNDAEL_128,
        $keyBytes, 
        $inputBytes, 
        MCRYPT_MODE_CBC, 
        $ivBytes
    );
    assert($outputBytes == $expectedOutputBytes);
}

function CryptorTests_testAES128PadCBCDecryption()
{
    $inputBytes = QIO_bytesWithContentsOfFile("cyphertext-aes-128-cbc-332.dat");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("plaintext-332.dat");
    $keyBytes = QHex_bytesFromHexString("0C1032520302EC8537A4A82C4EF7579D");
    $ivBytes = QHex_bytesFromHexString("AB5BBEB426015DA7EEDCEE8BEE3DFFB7");
    $outputBytes = mcrypt_decrypt(
        MCRYPT_RIJNDAEL_128,
        $keyBytes, 
        $inputBytes, 
        MCRYPT_MODE_CBC, 
        $ivBytes
    );
    
    // PHP doesn't have built-in support for PKCS#7 padding so strip it by hand.
    $padding = ord($outputBytes[strlen($outputBytes) - 1]); 
    $outputBytes = substr($outputBytes, 0, -$padding); 
    
    assert($outputBytes == $expectedOutputBytes);
}

// AES-256 Pad CBC

function CryptorTests_testAES256PadCBCEncryption()
{
    $inputBytes = QIO_bytesWithContentsOfFile("plaintext-332.dat");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("cyphertext-aes-256-cbc-332.dat");
    $keyBytes = QHex_bytesFromHexString("0C1032520302EC8537A4A82C4EF7579D2b88e4309655eb40707decdb143e328a");
    $ivBytes = QHex_bytesFromHexString("AB5BBEB426015DA7EEDCEE8BEE3DFFB7");

    // PHP doesn't have built-in support for PKCS#7 padding so we add it by hand.
    
    $pad = 16 - (strlen($inputBytes) % 16);
    $inputBytes .= str_repeat(chr($pad), $pad);

    $outputBytes = mcrypt_encrypt(
        MCRYPT_RIJNDAEL_128,
        $keyBytes, 
        $inputBytes, 
        MCRYPT_MODE_CBC, 
        $ivBytes
    );
    assert($outputBytes == $expectedOutputBytes);
}

function CryptorTests_testAES256PadCBCDecryption()
{
    $inputBytes = QIO_bytesWithContentsOfFile("cyphertext-aes-256-cbc-332.dat");
    $expectedOutputBytes = QIO_bytesWithContentsOfFile("plaintext-332.dat");
    $keyBytes = QHex_bytesFromHexString("0C1032520302EC8537A4A82C4EF7579D2b88e4309655eb40707decdb143e328a");
    $ivBytes = QHex_bytesFromHexString("AB5BBEB426015DA7EEDCEE8BEE3DFFB7");
    $outputBytes = mcrypt_decrypt(
        MCRYPT_RIJNDAEL_128,
        $keyBytes, 
        $inputBytes, 
        MCRYPT_MODE_CBC, 
        $ivBytes
    );
    
    // PHP doesn't have built-in support for PKCS#7 padding so strip it by hand.
    $padding = ord($outputBytes[strlen($outputBytes) - 1]); 
    $outputBytes = substr($outputBytes, 0, -$padding); 
    
    assert($outputBytes == $expectedOutputBytes);
}

// main

Base64Tests_testBase64Encode();
Base64Tests_testBase64Decode();
DigestTests_testSHA();
DigestTests_testHMACSHA();
KeyDerivationTests_testPBKDF2();
CryptorTests_testAES128ECBEncryption();
CryptorTests_testAES128ECBDecryption();
CryptorTests_testAES128CBCEncryption();
CryptorTests_testAES128CBCDecryption();
CryptorTests_testAES256ECBEncryption();
CryptorTests_testAES256ECBDecryption();
CryptorTests_testAES256CBCEncryption();
CryptorTests_testAES256CBCDecryption();
CryptorTests_testAES128PadCBCEncryption();
CryptorTests_testAES128PadCBCDecryption();
CryptorTests_testAES256PadCBCEncryption();
CryptorTests_testAES256PadCBCDecryption();
echo "Success!\n";
