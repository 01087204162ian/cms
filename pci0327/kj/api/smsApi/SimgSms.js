// 정현이가 보내온 nodejs. 파일

var unirest = require('unirest'); // unirset 모듈


let sendData = {
    "receiver": '01082077529',
    "msg": '문자테스트',
    "testmode_yn": 'N'
};

sendAligoSms(sendData)


function sendAligoSms(data){

    return new Promise(function (resolve, reject) {
        console.log('Aligo Lambda AWS SERVICE ~!');
        unirest.post('https://0j8iqqmk2l.execute-api.ap-northeast-2.amazonaws.com/aligo-send')
            .headers(
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                })
            .type('json')
            .json(data)
            .end(function (response) {
                console.log('from AWS LAMBDA_SEND: ', response.body);
                console.log('send ', data);
                let d = {
                    'receive':response.body,
                    'sendD':data

                }
                resolve(d);
            });
    });


}