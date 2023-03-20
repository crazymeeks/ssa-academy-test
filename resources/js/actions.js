
import api from "./axios/api";

export const deleteUser = () => {
    
    if ($('.user-listing').length > 0) {
        $('.action-delete').on('click', async function(evt){
            const id = $(this).data('id');
            try {
                await api.delete(`/users/${id}/delete`);
                window.location.href = window.location.href;
            } catch (e) {}
        });
    }
};

export const restoreUser = () => {
    
    if ($('.user-trash').length > 0) {
        $('.action-restore').on('click', async function(evt){
            const id = $(this).data('id');
            try {
                await api.patch(`/users/${id}/restore`);
                window.location.href = window.location.href;
            } catch (e) {}
        });
    }
};


export const permentlyDelete = () => {
    
    if ($('.user-trash').length > 0) {
        $('.action-permanently-delete').on('click', async function(evt){
            const id = $(this).data('id');
            try {
                await api.delete(`/users/${id}/permanent`);
                window.location.href = window.location.href;
            } catch (e) {}
        });
    }
};