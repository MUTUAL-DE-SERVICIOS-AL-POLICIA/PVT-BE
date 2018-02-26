// if some dynamic components are used frequently, a better way is to register them globally
export default {
  DisplayRow: require('./nested-DisplayRow'),
  Color: require('../../comps/td-Color'),
  CreatetimeTd: require('../../comps/td-Createtime'),
  CreatetimeTh: require('../../comps/th-Createtime'),
  Email: require('../../comps/td-Email'),
  IP: require('../../comps/td-IP'),
  Opt: require('./td-Opt'),
  FilterTh: require('../../comps/th-Filter')
  // [Vue warn]: Do not use built-in or reserved HTML elements as component id: Filter
  // Filter: require('./th-Filter')
}
