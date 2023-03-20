
import api from "./axios/api";

export const deleteUser = () => {
    
    if ($('.user-listing').length > 0) {
        $('.action-delete').on('click', async function(evt){
            const id = $(this).data('id');
            try {
                const response = await api.delete(`/users`, {
                    data: {
                        id: id
                    }
                });
                window.location.href = window.location.href;
            } catch (e) {}
        });
    }
};