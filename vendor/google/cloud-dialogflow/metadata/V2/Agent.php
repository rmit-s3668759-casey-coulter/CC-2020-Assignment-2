<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/dialogflow/v2/agent.proto

namespace GPBMetadata\Google\Cloud\Dialogflow\V2;

class Agent
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Api\Annotations::initOnce();
        \GPBMetadata\Google\Api\Client::initOnce();
        \GPBMetadata\Google\Api\FieldBehavior::initOnce();
        \GPBMetadata\Google\Api\Resource::initOnce();
        \GPBMetadata\Google\Cloud\Dialogflow\V2\ValidationResult::initOnce();
        \GPBMetadata\Google\Longrunning\Operations::initOnce();
        \GPBMetadata\Google\Protobuf\GPBEmpty::initOnce();
        \GPBMetadata\Google\Protobuf\FieldMask::initOnce();
        $pool->internalAddGeneratedFile(hex2bin(
            "0af1210a26676f6f676c652f636c6f75642f6469616c6f67666c6f772f76" .
            "322f6167656e742e70726f746f121a676f6f676c652e636c6f75642e6469" .
            "616c6f67666c6f772e76321a17676f6f676c652f6170692f636c69656e74" .
            "2e70726f746f1a1f676f6f676c652f6170692f6669656c645f6265686176" .
            "696f722e70726f746f1a19676f6f676c652f6170692f7265736f75726365" .
            "2e70726f746f1a32676f6f676c652f636c6f75642f6469616c6f67666c6f" .
            "772f76322f76616c69646174696f6e5f726573756c742e70726f746f1a23" .
            "676f6f676c652f6c6f6e6772756e6e696e672f6f7065726174696f6e732e" .
            "70726f746f1a1b676f6f676c652f70726f746f6275662f656d7074792e70" .
            "726f746f1a20676f6f676c652f70726f746f6275662f6669656c645f6d61" .
            "736b2e70726f746f22f0060a054167656e7412430a06706172656e741801" .
            "200128094233e04102fa412d0a2b636c6f75647265736f757263656d616e" .
            "616765722e676f6f676c65617069732e636f6d2f50726f6a65637412190a" .
            "0c646973706c61795f6e616d651802200128094203e0410212220a156465" .
            "6661756c745f6c616e67756167655f636f64651803200128094203e04102" .
            "12250a18737570706f727465645f6c616e67756167655f636f6465731804" .
            "200328094203e0410112160a0974696d655f7a6f6e651805200128094203" .
            "e0410212180a0b6465736372697074696f6e1806200128094203e0410112" .
            "170a0a6176617461725f7572691807200128094203e04101121b0a0e656e" .
            "61626c655f6c6f6767696e671808200128084203e0410112440a0a6d6174" .
            "63685f6d6f646518092001280e322b2e676f6f676c652e636c6f75642e64" .
            "69616c6f67666c6f772e76322e4167656e742e4d617463684d6f64654203" .
            "e0410112250a18636c617373696669636174696f6e5f7468726573686f6c" .
            "64180a200128024203e0410112460a0b6170695f76657273696f6e180e20" .
            "01280e322c2e676f6f676c652e636c6f75642e6469616c6f67666c6f772e" .
            "76322e4167656e742e41706956657273696f6e4203e0410112390a047469" .
            "6572180f2001280e32262e676f6f676c652e636c6f75642e6469616c6f67" .
            "666c6f772e76322e4167656e742e546965724203e0410122560a094d6174" .
            "63684d6f6465121a0a164d415443485f4d4f44455f554e53504543494649" .
            "4544100012150a114d415443485f4d4f44455f485942524944100112160a" .
            "124d415443485f4d4f44455f4d4c5f4f4e4c591002226c0a0a4170695665" .
            "7273696f6e121b0a174150495f56455253494f4e5f554e53504543494649" .
            "4544100012120a0e4150495f56455253494f4e5f5631100112120a0e4150" .
            "495f56455253494f4e5f5632100212190a154150495f56455253494f4e5f" .
            "56325f424554415f311003225e0a045469657212140a10544945525f554e" .
            "535045434946494544100012110a0d544945525f5354414e444152441001" .
            "12130a0f544945525f454e5445525052495345100212180a14544945525f" .
            "454e54455250524953455f504c555310033a3eea413b0a1f6469616c6f67" .
            "666c6f772e676f6f676c65617069732e636f6d2f4167656e74121870726f" .
            "6a656374732f7b70726f6a6563747d2f6167656e7422560a0f4765744167" .
            "656e745265717565737412430a06706172656e741801200128094233e041" .
            "02fa412d0a2b636c6f75647265736f757263656d616e616765722e676f6f" .
            "676c65617069732e636f6d2f50726f6a656374227e0a0f5365744167656e" .
            "745265717565737412350a056167656e7418012001280b32212e676f6f67" .
            "6c652e636c6f75642e6469616c6f67666c6f772e76322e4167656e744203" .
            "e0410212340a0b7570646174655f6d61736b18022001280b321a2e676f6f" .
            "676c652e70726f746f6275662e4669656c644d61736b4203e0410122590a" .
            "1244656c6574654167656e745265717565737412430a06706172656e7418" .
            "01200128094233e04102fa412d0a2b636c6f75647265736f757263656d61" .
            "6e616765722e676f6f676c65617069732e636f6d2f50726f6a6563742286" .
            "010a135365617263684167656e74735265717565737412430a0670617265" .
            "6e741801200128094233e04102fa412d0a2b636c6f75647265736f757263" .
            "656d616e616765722e676f6f676c65617069732e636f6d2f50726f6a6563" .
            "7412160a09706167655f73697a651802200128054203e0410112120a0a70" .
            "6167655f746f6b656e18032001280922620a145365617263684167656e74" .
            "73526573706f6e736512310a066167656e747318012003280b32212e676f" .
            "6f676c652e636c6f75642e6469616c6f67666c6f772e76322e4167656e74" .
            "12170a0f6e6578745f706167655f746f6b656e18022001280922580a1154" .
            "7261696e4167656e745265717565737412430a06706172656e7418012001" .
            "28094233e04102fa412d0a2b636c6f75647265736f757263656d616e6167" .
            "65722e676f6f676c65617069732e636f6d2f50726f6a65637422710a1245" .
            "78706f72744167656e745265717565737412430a06706172656e74180120" .
            "0128094233e04102fa412d0a2b636c6f75647265736f757263656d616e61" .
            "6765722e676f6f676c65617069732e636f6d2f50726f6a65637412160a09" .
            "6167656e745f7572691802200128094203e04102224c0a134578706f7274" .
            "4167656e74526573706f6e736512130a096167656e745f75726918012001" .
            "2809480012170a0d6167656e745f636f6e74656e7418022001280c480042" .
            "070a056167656e742290010a12496d706f72744167656e74526571756573" .
            "7412430a06706172656e741801200128094233e04102fa412d0a2b636c6f" .
            "75647265736f757263656d616e616765722e676f6f676c65617069732e63" .
            "6f6d2f50726f6a65637412130a096167656e745f75726918022001280948" .
            "0012170a0d6167656e745f636f6e74656e7418032001280c480042070a05" .
            "6167656e742291010a13526573746f72654167656e745265717565737412" .
            "430a06706172656e741801200128094233e04102fa412d0a2b636c6f7564" .
            "7265736f757263656d616e616765722e676f6f676c65617069732e636f6d" .
            "2f50726f6a65637412130a096167656e745f757269180220012809480012" .
            "170a0d6167656e745f636f6e74656e7418032001280c480042070a056167" .
            "656e74227d0a1a47657456616c69646174696f6e526573756c7452657175" .
            "65737412430a06706172656e741801200128094233e04102fa412d0a2b63" .
            "6c6f75647265736f757263656d616e616765722e676f6f676c6561706973" .
            "2e636f6d2f50726f6a656374121a0a0d6c616e67756167655f636f646518" .
            "03200128094203e0410132c50d0a064167656e7473128a010a0847657441" .
            "67656e74122b2e676f6f676c652e636c6f75642e6469616c6f67666c6f77" .
            "2e76322e4765744167656e74526571756573741a212e676f6f676c652e63" .
            "6c6f75642e6469616c6f67666c6f772e76322e4167656e74222e82d3e493" .
            "021f121d2f76322f7b706172656e743d70726f6a656374732f2a7d2f6167" .
            "656e74da4106706172656e741296010a085365744167656e74122b2e676f" .
            "6f676c652e636c6f75642e6469616c6f67666c6f772e76322e5365744167" .
            "656e74526571756573741a212e676f6f676c652e636c6f75642e6469616c" .
            "6f67666c6f772e76322e4167656e74223a82d3e493022c22232f76322f7b" .
            "6167656e742e706172656e743d70726f6a656374732f2a7d2f6167656e74" .
            "3a056167656e74da41056167656e741285010a0b44656c6574654167656e" .
            "74122e2e676f6f676c652e636c6f75642e6469616c6f67666c6f772e7632" .
            "2e44656c6574654167656e74526571756573741a162e676f6f676c652e70" .
            "726f746f6275662e456d707479222e82d3e493021f2a1d2f76322f7b7061" .
            "72656e743d70726f6a656374732f2a7d2f6167656e74da4106706172656e" .
            "7412a8010a0c5365617263684167656e7473122f2e676f6f676c652e636c" .
            "6f75642e6469616c6f67666c6f772e76322e5365617263684167656e7473" .
            "526571756573741a302e676f6f676c652e636c6f75642e6469616c6f6766" .
            "6c6f772e76322e5365617263684167656e7473526573706f6e7365223582" .
            "d3e493022612242f76322f7b706172656e743d70726f6a656374732f2a7d" .
            "2f6167656e743a736561726368da4106706172656e7412c5010a0a547261" .
            "696e4167656e74122d2e676f6f676c652e636c6f75642e6469616c6f6766" .
            "6c6f772e76322e547261696e4167656e74526571756573741a1d2e676f6f" .
            "676c652e6c6f6e6772756e6e696e672e4f7065726174696f6e226982d3e4" .
            "93022822232f76322f7b706172656e743d70726f6a656374732f2a7d2f61" .
            "67656e743a747261696e3a012ada4106706172656e74ca412f0a15676f6f" .
            "676c652e70726f746f6275662e456d7074791216676f6f676c652e70726f" .
            "746f6275662e53747275637412e2010a0b4578706f72744167656e74122e" .
            "2e676f6f676c652e636c6f75642e6469616c6f67666c6f772e76322e4578" .
            "706f72744167656e74526571756573741a1d2e676f6f676c652e6c6f6e67" .
            "72756e6e696e672e4f7065726174696f6e22830182d3e493022922242f76" .
            "322f7b706172656e743d70726f6a656374732f2a7d2f6167656e743a6578" .
            "706f72743a012ada4106706172656e74ca41480a2e676f6f676c652e636c" .
            "6f75642e6469616c6f67666c6f772e76322e4578706f72744167656e7452" .
            "6573706f6e73651216676f6f676c652e70726f746f6275662e5374727563" .
            "7412bf010a0b496d706f72744167656e74122e2e676f6f676c652e636c6f" .
            "75642e6469616c6f67666c6f772e76322e496d706f72744167656e745265" .
            "71756573741a1d2e676f6f676c652e6c6f6e6772756e6e696e672e4f7065" .
            "726174696f6e226182d3e493022922242f76322f7b706172656e743d7072" .
            "6f6a656374732f2a7d2f6167656e743a696d706f72743a012aca412f0a15" .
            "676f6f676c652e70726f746f6275662e456d7074791216676f6f676c652e" .
            "70726f746f6275662e53747275637412c2010a0c526573746f7265416765" .
            "6e74122f2e676f6f676c652e636c6f75642e6469616c6f67666c6f772e76" .
            "322e526573746f72654167656e74526571756573741a1d2e676f6f676c65" .
            "2e6c6f6e6772756e6e696e672e4f7065726174696f6e226282d3e493022a" .
            "22252f76322f7b706172656e743d70726f6a656374732f2a7d2f6167656e" .
            "743a726573746f72653a012aca412f0a15676f6f676c652e70726f746f62" .
            "75662e456d7074791216676f6f676c652e70726f746f6275662e53747275" .
            "637412b3010a1347657456616c69646174696f6e526573756c7412362e67" .
            "6f6f676c652e636c6f75642e6469616c6f67666c6f772e76322e47657456" .
            "616c69646174696f6e526573756c74526571756573741a2c2e676f6f676c" .
            "652e636c6f75642e6469616c6f67666c6f772e76322e56616c6964617469" .
            "6f6e526573756c74223682d3e4930230122e2f76322f7b706172656e743d" .
            "70726f6a656374732f2a7d2f6167656e742f76616c69646174696f6e5265" .
            "73756c741a78ca41196469616c6f67666c6f772e676f6f676c6561706973" .
            "2e636f6dd2415968747470733a2f2f7777772e676f6f676c65617069732e" .
            "636f6d2f617574682f636c6f75642d706c6174666f726d2c68747470733a" .
            "2f2f7777772e676f6f676c65617069732e636f6d2f617574682f6469616c" .
            "6f67666c6f774299010a1e636f6d2e676f6f676c652e636c6f75642e6469" .
            "616c6f67666c6f772e7632420a4167656e7450726f746f50015a44676f6f" .
            "676c652e676f6c616e672e6f72672f67656e70726f746f2f676f6f676c65" .
            "617069732f636c6f75642f6469616c6f67666c6f772f76323b6469616c6f" .
            "67666c6f77f80101a202024446aa021a476f6f676c652e436c6f75642e44" .
            "69616c6f67666c6f772e5632620670726f746f33"
        ), true);

        static::$is_initialized = true;
    }
}
