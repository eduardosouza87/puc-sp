class Loader {
	static stopLoading() {
		document.body.classList.add("is-loaded");
	}

	static startLoading() {
		document.body.classList.remove("is-loaded");
	}
}

export default Loader;
