const state = {
  workflows: [],
  filterCi: null,
};
const mutations = {
    pushDoc(state, workflow) {
        if (state.workflows.length > 0) {
            let index = state.workflows.findIndex(o => o.workflow_id == workflow.workflow_id);
            if (index >= 0) {
                let indexDoc = state.workflows[index].docs.findIndex(d => d.id == workflow.doc.id);
                if (indexDoc >= 0 && !workflow.doc.status) {
                  state.workflows[index].docs.splice(indexDoc,1);
                } else {
                  state.workflows[index].docs.push(workflow.doc);
                }
            }else{
                state.workflows.push({ workflow_id: workflow.workflow_id, docs: [workflow.doc] });
            }
        }else{
            state.workflows.push({workflow_id: workflow.workflow_id, docs:[workflow.doc] });
        }
    },
    clear(state, workflow_id){
        let index = state.workflows.findIndex(o => o.workflow_id == workflow_id);
        if (index >= 0) {
            state.workflows[index].docs = [];
        }else{
            alert("hubo algun error");
        }
    },
    search(state, field){
        state.filterCi = field; 
    }
}
const getters = {
    getDataInbox: state => state
}
export default {
    state,
    mutations,
    getters,
    namespaced: true,
}
