var Cp = window.Cp || {};

/**
 * 常用AJAX
 * @module Cp.common
 */
(function (Cp) {

    Cp.ajax = {
        /**
         * ajax 请求
         * @param params
         * {
		 * type:'POST',data:请求数据, href:ajax请求url, successTitle:成功提示,  successFnc:成功回调, errorFnc:失败回调
		 * }
         */
        request: function (params) {
            var params = params || {},
                _type = params.type || 'POST',
                _data = params.data || {},
                _successFnc = params.successFnc || function () {
                        window.location.reload();
                    },
                _successTitle = params.successTitle || '操作成功',
                _errorFnc = params.errorFnc || function () {
                        swal('操作失败', 'error');
                    };

            $.ajax({
                url: params.href, type: _type, data: _data
            }).done(function (data) {
                if (data.errorCode == 00000) {
                    swal({
                        title: _successTitle,
                        type: 'success',
                        confirmButtonColor: '#8CD4F5',
                        closeOnConfirm: false
                    }, function () {
                        if(data.route){
                            window.location.href = data.route;
                        }else{
                            _successFnc()
                        }
                    });
                } else if (data.errorCode != '') {
                    swal(data.message, '', 'error');
                } else {
                    _errorFnc()
                }
            }).fail(function () {
                swal('服务器请求错误', '', 'error');
            });
        },

        /**
         * 删除单条记录
         * @param params
         * {
		 * data:请求数据, confirmTitle:提示标题, href:ajax请求url, successTitle:删除成功提示,  successFnc:删除成功回调, errorFnc:删除失败回调
		 * }
         * @returns {jQuery}
         */
        delete: function (params) {
            var params = params || {},
                _confirmTitle = params.confirmTitle || '确定删除该记录吗?',
                _successFnc = params.successFnc || function () {
                        window.location.reload();
                    },
                _successTitle = params.successTitle || '删除成功',
                _errorFnc = params.errorFnc || function () {
                        swal('删除失败', 'error');
                    }, _this = this;
            swal({
                title: _confirmTitle,
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                if (params.type == undefined) {
                    params.type = 'DELETE';
                }
                _this.request(params);
            });
        },

        /**
         *
         * @param params
         * {
		 * confirmTitle:提示标题, href:ajax请求url, successTitle:删除成功提示,  successFnc:删除成功回调, errorFnc:删除失败回调
		 * }
         * @returns {jQuery}
         */
        deleteAll: function (params) {
            var ids = [];
            $(".selectall-item").each(function (e) {
                if ($(this).prop('checked')) {
                    ids.push($(this).val());
                }
            });

            if (ids.length == 0) {
                swal('请选择需要删除的记录', '', 'warning');
                return false;
            }
            params.data = {ids: ids};
            params.type = 'POST';
            this.delete(params);
        },

        /**
         * @param 33333
         * {
         * successTitle:提示标题, href:ajax请求url, errorFnc:失败回调
         * }
         * @return {[type]}
         */
        draw:function(params){
            var params = params || {},
                _confirmTitle = params.confirmTitle || '22222',
                _type = params.type || 'POST',
                _data = params.data || {},
                _successFnc = params.successFnc || function () {
                        window.location.reload();
                    },
                _successTitle = params.successTitle || '操作成功',
                _text = params.text || '注意只能输入数字',
                _errorFnc = params.errorFnc || function () {
                        swal('操作失败', 'error');
                }, _this = this;

            swal({
                title: _confirmTitle,
                type: "input",
                text: _text,
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                animation: "slide-from-top",
                inputPlaceholder: "12345"
            }, function (inputValue) {
                if (inputValue === false) return false;

                if (inputValue === "") {
                    swal.showInputError("11111");
                    return false
                }

                if (params.type == undefined) {
                    params.type = 'POST';
                }

                _data['issue'] = inputValue;
                params.data = _data;
                _this.request(params);
            });
        },


    };
})(Cp);


//检测输入长度
function maxLengthCheck(object){
if (object.value.length > object.maxLength)
  object.value = object.value.slice(0, object.maxLength);
}