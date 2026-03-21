import http from "./http.js";

export async function toggleTaskCompletion(taskId, button) {
    const url = `/tasks/${taskId}/toggle-complete`;

    button.diasbled = true;

    try {
        const response = await http.post(url);

        return response.data;
    } catch (error) {
        console.log(error);
    } finally {
        button.disabled = false;
    }
}
