'use strict';
//node .\node_modules\mocha\bin\mocha .\src\client\tests\test.js
// or all files in this directory:
//node .\node_modules\mocha\bin\mocha .\src\client\tests
// to watch test folder for chnages:
//node .\node_modules\mocha\bin\mocha .\src\client\tests -w

var chai = require('chai'),
    expect = chai.expect;

chai.should();

function isEven(num) {
    return num % 2 === 0;
}
function add(num1, num2)  {
    return num1 + num2;
}

describe('isEven', function () {
    it('should return true when number is even', function () {
        isEven(4).should.be.true;
    });
    it('should return false when number is odd', function () {
        expect(isEven(5)).to.be.false;
    });
});


describe('add', function () {
    var num;
    beforeEach(function () {
        num = 5;
    });

    afterEach(function () {

    });

    it('should return 10 when adding 5 to 5', function () {
        num = add(num, 5);
        num.should.equal(10);
    });

    //xit( or it.skip( - to exclude test for now
    it('should return 12 when adding 7 to 5', function () {
        add(num, 7).should.equal(12);
    });
});